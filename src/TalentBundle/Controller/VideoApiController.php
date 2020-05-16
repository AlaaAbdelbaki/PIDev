<?php

namespace TalentBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\video;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class VideoApiController extends Controller
{
    public function getVideosAction($owner)
    {

        $videos= $this->getDoctrine()->getManager()->getRepository(video::class)->findBy(["owner"=>$owner]);
        $normalizer = new ObjectNormalizer();
        $normalizer = $normalizer->setCircularReferenceHandler(function ($owner){
            return $owner->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($videos,null,[ObjectNormalizer::ATTRIBUTES=>['id',"url","title","publish_date","owner"=>["id","email","username","name","firstName","sexe","adresse","telephoneNumber","bio","roles","birthday","profilePic"]]]);
        return new JsonResponse($formatted);
    }



    public function addVideoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $video = new video();
        $video->setUrl($request->get('url'));
        $video->setTitle($request->get('title'));
        $video->setPublishDate(new \DateTime());
        $video->setOwner($this->getDoctrine()->getManager()->getRepository(User::class)->find($request->get('id')));
        $em->persist($video);
        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer = $normalizer->setCircularReferenceHandler(function ($owner){
            return $owner->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($video);
        return new JsonResponse($formatted);

    }

    public function deleteVideoAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $video =$em->getRepository(video::class)->find($id);
        $em->remove($video);
        $em->flush();
        return true;
    }
}
