<?php

namespace CompetitionsBundle\Controller;

use AppBundle\Entity\competition;
use AppBundle\Entity\competition_participant;
use AppBundle\Entity\Message;
use AppBundle\Entity\User;
use AppBundle\Entity\video;
use AppBundle\Entity\vote;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\PaginatorInterface;
use Swift_Attachment;
use Swift_Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class competitionController extends Controller
{
    /**
     * Lists all competition entities.
     *
     * @Route("/competition", name="competition_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $em = $this->getDoctrine()->getManager();

        $competitions = $em->getRepository('AppBundle:competition')->findAll();
        $part = $em->getRepository('AppBundle:competition_participant')->findByUser($this->getUser());
        $c = new ArrayCollection();
        foreach ($part as $p) {
            $c->add($p->getCompetition());
        }
        dump($c);
        return $this->render('CompetitionsBundle:Default:index.html.twig', array(
            'competitions' => $competitions, 'c' => $c
        ));
    }

    /**
     * Lists all competition entities for the admin.
     *
     * @Route("/admin/competition", name="admin_competition_index")
     * @Method("GET")
     */
    public function adminIndexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $competitions = $em->getRepository('AppBundle:competition')->findAll();
        foreach ($competitions as $comp) {
            $ranks = $this->getDoctrine()->getRepository(competition_participant::class)->findRanks($comp->getId());
            if ($ranks != null) {
                $vid = $this->getDoctrine()->getRepository(video::class)->findById($ranks[0]['video_id']);

                $winner = $vid[0]->getOwner();
                $comp->setWinner($winner);

            }
        }
        return $this->render('CompetitionsBundle:Default:admin_index.html.twig', array(
            'competitions' => $competitions,
        ));
    }


    /**
     * Creates a new competition entity.
     *
     * @Route("admin/competition/new", name="competition_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $competition = new Competition();
        $form = $this->createForm('CompetitionsBundle\Form\competitionType', $competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($competition);
            $em->flush();

            return $this->redirectToRoute('admin_competition_index');
        }

        return $this->render('CompetitionsBundle:Default:new.html.twig', array(
            'competition' => $competition,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing competition entity.
     *
     * @Route("admin/competition/edit/{id}", name="competition_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param competition $id
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request, competition $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $competition = new competition();
        $competition = $this->getDoctrine()->getRepository(competition::class)->find($id);
        $editForm = $this->createForm('CompetitionsBundle\Form\competitionType', $competition);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_competition_index');
        }

        return $this->render('CompetitionsBundle:Default:edit.html.twig', array(

            'form' => $editForm->createView(),

        ));
    }

    /**
     * Deletes a competition entity.
     *
     * @Route("admin/competition/delete/{id}", name="competition_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param competition $id
     * @return Response
     */
    public function deleteAction(Request $request, competition $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $competition = $this->getDoctrine()->getRepository(competition::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($competition);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('admin_competition_index');
    }

    /**
     * Creates a new competition entity.
     *
     * @Route("competition/participate/{id}", name="competition_participate")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param competition $id
     * @return RedirectResponse|Response
     */
    public function newVideoAction(Request $request, competition $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $competition = $this->getDoctrine()->getRepository(competition::class)->find($id);
        $participation = $this->getDoctrine()->getRepository(competition_participant::class)->findByUser($this->getUser());
        $m = $participation = $this->getDoctrine()->getRepository(competition_participant::class)->findBy(['user' => $this->getUser(), 'competition' => $competition]);
        if (($m != null) || in_array('ROLE_TALENTED', $this->getUser()->getRoles())) return $this->redirectToRoute('competition_index');
        $video = new video();
        $form = $this->createForm('CompetitionsBundle\Form\videoType', $video);
        $form->handleRequest($request);
        $link = "https://www.youtube.com/embed/";
        if ($form->isSubmitted() && $form->isValid()) {
            $video->setOwner($this->getUser());
            $url = $video->getUrl();
            $link = $link . substr($url, -11);
            $video->setUrl($link);
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $participation = new competition_participant();
            $user = $this->getUser();
            $participation->setCompetition($competition);
            $participation->setParticipationDate($video->getPublishDate());
            $participation->setUser($user);
            $participation->setVideo($video);
            $em->persist($participation);
            $em->flush();

            return $this->redirectToRoute('competition_index');
        }

        return $this->render('CompetitionsBundle:Default:participation.html.twig', array(
            'video' => $video, 'participant' => $participation,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/competition/{id}", name="competition_show")
     * @param competition $id
     * @return Response
     */
    public function show($id, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $paginator = $this->get(PaginatorInterface::class);
        $competition = $this->getDoctrine()->getRepository(competition::class)->find($id);
        $participations = $this->getDoctrine()->getRepository(competition_participant::class)->findByCompetition($id);
        $pagination = $paginator->paginate($participations, $request->query->getInt('page', 1), 3);
        $ranks = $this->getDoctrine()->getRepository(competition_participant::class)->findRanks($id);

        $res = new ArrayCollection();
        foreach ($ranks as $r) {
            $vid = $this->getDoctrine()->getRepository(video::class)->findById($r['video_id']);

            $res->add($vid);

        }

        return ($this->render('@Competitions/Default/competition_show.html.twig', array('competition' => $competition, 'participations' => $pagination, 'ranks' => $res))
        );
    }

    /**
     * Deletes a competition entity.
     *
     * @Route("/competition/participation/delete/{id}", name="participation_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param competition_participant $id
     * @return Response
     */
    public function participationDeleteAction(Request $request, competition_participant $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $participation = $this->getDoctrine()->getRepository(competition_participant::class)->find($id);
        $video = $participation->getVideo();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($participation);
        $entityManager->remove($video);
        $entityManager->flush();

        $response = new Response();
        $response->send();

        return $this->redirectToRoute('competition_show', ['id' => $participation->getcompetition()->getid()]);
    }

    /**
     * Displays a form to edit an existing competition entity.
     *
     * @Route("/participation/{id}", name="participation_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param competition_participant $id
     * @return RedirectResponse|Response
     */
    public function participationEditAction(Request $request, competition_participant $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $participation = new competition_participant();
        $participation = $this->getDoctrine()->getRepository(competition_participant::class)->find($id);
        $video = $participation->getVideo();
        $editForm = $this->createForm('CompetitionsBundle\Form\videoType', $video);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('competition_show', ['id' => $participation->getcompetition()->getid()]);
        }

        return $this->render('CompetitionsBundle:Default:participation_edit.html.twig', array(

            'form' => $editForm->createView(), 'participation' => $participation

        ));
    }

    /**
     * @Route("/vote/{id}", name="competition_vote")
     *
     */
    public function voteAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $video = $this->getDoctrine()->getRepository(video::class)->find($id);
        $video->getVotes()->add($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($video);
        $em->flush();
        return new Response();

    }

    /**
     * @Route("/DownVote/{id}", name="competition_downVote")
     *
     */
    public function downVoteAction($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $video = $this->getDoctrine()->getRepository(video::class)->find($id);
        $video->getVotes()->removeElement($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($video);
        $em->flush();
        return new Response();

    }

    /**
     * @Route("/ranking/{id}", name="update_ranks")
     * @param competition $id
     * @return Response
     */
    public function updateRanksAction($id)
    {

        $participations = $this->getDoctrine()->getRepository(competition_participant::class)->findByCompetition($id);

        $ranks = $this->getDoctrine()->getRepository(competition_participant::class)->findRanks($id);

        $res = new ArrayCollection();
        foreach ($ranks as $r) {
            $vid = $this->getDoctrine()->getRepository(video::class)->findById($r['video_id']);

            $res->add($vid);

        }

        return ($this->render('@Competitions/Default/ranks.html.twig', array('ranks' => $res))
        );
    }

    /**
     * @Route("admin/promote/{id}", name="promote_user")
     * @param User $id
     * @return Response
     *
     */
    public function promoteUserAction($id)
    {

        $talented = $this->getDoctrine()->getRepository(User::class)->find($id);
        $message = (new \Swift_Message('Congratulations Email'))
            ->setFrom('zribisarahzribi@gmail.com')
            ->setTo($talented->getEmailCanonical());
        $message->setBody(
        '<html>' .
        ' <body>' .
        '  Congrats <img src="' .
        $message->embed(Swift_Image::fromPath('D:\Projects\PIDev\web\assets\img\congrats.jpg')) .
        '" alt="Image" />' .
        '  You earned a Talented Account' .
        ' </body>' .
        '</html>',
        'text/html');
        $this->get('mailer')->send($message);
        $talented->setRoles(['ROLE_TALENTED']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($talented);
        $em->flush();
        return $this->redirectToRoute('admin_competition_index');


    }
}
