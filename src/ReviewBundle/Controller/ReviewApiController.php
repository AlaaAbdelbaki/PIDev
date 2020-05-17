<?php

namespace ReviewBundle\Controller;

use AppBundle\Entity\review;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ReviewApiController extends Controller
{
    public function allReviewAction()
    {
        $tasks=$this->getDoctrine()->getManager()
            ->getRepository(review::class)
            ->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formated=$serializer->normalize($tasks);
        return new JsonResponse($formated);
    }
    public function AjouterRJsonAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $review = new review();
        $review->setRating($request->get('rating'));
        $review->setCategory($request->get('category'));
        $review->setTitle($request->get('title'));
        $review->setContent($request->get('content'));
        $review->setUser($this->getDoctrine()->getRepository(User::class)->find($request->query->get('user_id')));
        $em->persist($review);
        $em->flush();


        return new JsonResponse();
    }
}

