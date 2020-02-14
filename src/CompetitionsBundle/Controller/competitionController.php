<?php

namespace CompetitionsBundle\Controller;

use AppBundle\Entity\competition;
use AppBundle\Entity\competition_participant;
use AppBundle\Entity\User;
use AppBundle\Entity\video;
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
        $em = $this->getDoctrine()->getManager();

        $competitions = $em->getRepository('AppBundle:competition')->findAll();

        return $this->render('CompetitionsBundle:Default:index.html.twig', array(
            'competitions' => $competitions,
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
    {$this->denyAccessUnlessGranted('ROLE_ADMIN');
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
    { $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $competition=new competition();
        $competition =$this->getDoctrine()->getRepository(competition::class)->find($id);
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
        $competition =$this->getDoctrine()->getRepository(competition::class)->find($id);

        $entityManager= $this->getDoctrine()->getManager();
        $entityManager->remove($competition);
        $entityManager->flush();

        $response= new Response();
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
    public function newVideoAction(Request $request,competition $id)
    {$competition =$this->getDoctrine()->getRepository(competition::class)->find($id);
        $video = new video();
        $form = $this->createForm('CompetitionsBundle\Form\videoType', $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $participation=new competition_participant();
            $user=$this->getUser();
            $participation->setCompetition($competition);
            $participation->setParticipationDate($video->getPublishDate());
            $participation->setUser($user);
            $participation->setVideo($video);
            $em->persist($participation);
            $em->flush();

            return $this->redirectToRoute('competition_index');
        }

        return $this->render('CompetitionsBundle:Default:participation.html.twig', array(
            'video' => $video,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/competition/{id}", name="competition_show")
     * @param competition $id
     * @return Response
     */
    public function show($id)
    {
        $competition =$this->getDoctrine()->getRepository(competition::class)->find($id);
        $participations =$this->getDoctrine()->getRepository(competition_participant::class)->findByCompetition($id);
        return($this->render('@Competitions/Default/competition_show.html.twig',array('competition' =>$competition ,'participations'=>$participations)));
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
        $participation =$this->getDoctrine()->getRepository(competition_participant::class)->find($id);
        $video=$participation->getVideo();
        $entityManager= $this->getDoctrine()->getManager();
        $entityManager->remove($participation);
        $entityManager->remove($video);
        $entityManager->flush();

        $response= new Response();
        $response->send();

        return $this->redirectToRoute('competition_show',['id'=>$participation->getcompetition()->getid()]);
    }
    /**
     * Displays a form to edit an existing competition entity.
     *
     * @Route("admin/participation/{id}", name="participation_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param competition_participant $id
     * @return RedirectResponse|Response
     */
    public function participationEditAction(Request $request, competition_participant $id)
    {
        $participation=new competition_participant();
        $participation =$this->getDoctrine()->getRepository(competition_participant::class)->find($id);
        $video=$participation->getVideo();
        $editForm = $this->createForm('CompetitionsBundle\Form\videoType', $video);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('competition_show',['id'=>$participation->getcompetition()->getid()]);
        }

        return $this->render('CompetitionsBundle:Default:participation_edit.html.twig', array(

            'form' => $editForm->createView(),'participation'=>$participation

        ));
    }
}
