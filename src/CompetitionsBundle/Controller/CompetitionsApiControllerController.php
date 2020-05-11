<?php

namespace CompetitionsBundle\Controller;

use AppBundle\Entity\competition;
use AppBundle\Entity\competition_participant;
use AppBundle\Entity\User;
use AppBundle\Entity\video;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CompetitionsApiControllerController extends Controller
{
    /**
     * Lists all competition entities.
     *
     * @Route("/api/competitions", name="api_competitions_index")
     *
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $competitions = $em->getRepository('AppBundle:competition')->findAll();

        $normalizer = new ObjectNormalizer ();

        $normalizer -> setCircularReferenceHandler ( function ( $comp ) {
            return $comp -> getId ();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($competitions , null , [ ObjectNormalizer::ATTRIBUTES => ['id','subject','competitionDate','competitionEndDate']]);

        return new JsonResponse($formatted);

    }

    /**
     * Lists all participation entities.
     *
     * @Route("/api/competitions/details/{id}", name="api_competitions_details")
     * @param $id
     * @return JsonResponse
     */
    public function showAction($id)
    {

        $participations = $this->getDoctrine()->getRepository(competition_participant::class)->findByCompetition($id);
  //dump($participations[0]->getVideo()->getVotes()[2]);
        $normalizer = new ObjectNormalizer ();

        $normalizer -> setCircularReferenceHandler ( function ( $participation ) {
            return $participation -> getId ();
        });

        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($participations , null , [ ObjectNormalizer::ATTRIBUTES => ['id','user'=>['id','username','email','roles','birthday','profilePic','sexe','telephoneNumber','adresse','name','firstName','bio'],'participationDate','video'
        =>['id','title','url','publishDate','owner'=>['id','username','email','roles','birthday','profilePic','sexe','telephoneNumber','adresse','name','firstName','bio'],
                'votes'=>['id']
            ]]]);

        return new JsonResponse($formatted);

    }

    /**
     * Lists all participation entities.
     *
     * @Route("/api/competitions/ranks/{id}", name="api_competitions_ranks")
     *
     */
    public function ranksAction($id)
    {

        $ranks = $this->getDoctrine()->getRepository(competition_participant::class)->findRanks($id);

        $res = array();
        foreach ($ranks as $r) {
            $vid = $this->getDoctrine()->getRepository(video::class)->findById($r['video_id']);

            array_push($res,$vid[0]);

        }

        $normalizer = new ObjectNormalizer ();

        $normalizer -> setCircularReferenceHandler ( function ( $rank ) {
            return $rank -> getId ();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($res , null , [ ObjectNormalizer::ATTRIBUTES => ['id','title','url','publishDate','owner'=>['id','username','email','roles','birthday','profilePic','sexe','telephoneNumber','adresse','name','firstName','bio']]]);

        return new JsonResponse($formatted);

    }

    /**
     *
     *
     * @Route("/api/competition/participate/", name="api_competitions_participate")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function participateAction(Request $request)
    {


     $r=$request->query->get('video');

      $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);
        $video = $serializer->deserialize($r, video::class, 'json');
        $u=$this->getDoctrine()->getRepository(User::class)->find($video->getOwner()['id']);
        $video->setOwner($u);
        $video->setPublishdate(new \DateTime('now'));
      // dump($video);

           $r2=$request->query->get('participation');
         $participation= $serializer->deserialize($r2, competition_participant::class, 'json');
        $c=$this->getDoctrine()->getRepository(competition::class)->find($participation->getCompetition()['id']);
        $participation->setUser($u);
        $participation->setCompetition($c);
        $participation->setVideo($video);
        $participation->setParticipationdate($video->getPublishdate());
      // dump($participation);*/
        $em = $this->getDoctrine()->getManager();
        $em->persist($video);
        $em->persist($participation);
        $em->flush();
     // dump($r);
        return new JsonResponse();

    }

    /**
     *
     *
     * @Route("/api/competition/participated/", name="api_competitions_participated")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function participatedAction(Request $request)
    {
        $r=$this->getDoctrine()->getRepository(competition_participant::class)->findParticipation($request->query->get('user'),$request->query->get('competition'));
if ($r) $v=true; else $v=false;

//dump($v);
return new JsonResponse($v);
    }

    /**
     * @Route("/api/vote/", name="competition_api_vote")
     * @param Request $Request
     * @return JsonResponse
     */
    public function voteAction(Request $Request)
    {

        $user = $this->getDoctrine()->getRepository(User::class)->find($Request->query->get('user'));
        $video = $this->getDoctrine()->getRepository(video::class)->find($Request->query->get('video'));
        $video->getVotes()->add($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($video);
        $em->flush();
        return new JsonResponse();

    }

    /**
     * @Route("/api/unvote/", name="competition_api_unvote")
     * @param Request $Request
     * @return JsonResponse
     */
    public function unvoteAction(Request $Request)
    {

        $user = $this->getDoctrine()->getRepository(User::class)->find($Request->query->get('user'));
        $video = $this->getDoctrine()->getRepository(video::class)->find($Request->query->get('video'));
        $video->getVotes()->removeElement($user);
        $em = $this->getDoctrine()->getManager();
        $em->persist($video);
        $em->flush();
        return new JsonResponse();

    }
}
