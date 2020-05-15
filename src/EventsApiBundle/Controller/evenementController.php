<?php

namespace EventsApiBundle\Controller;

use AppBundle\Entity\event;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;

class evenementController extends Controller
{
    public function afficheEventAction()
    {

        $tab = $this->getDoctrine()->getManager()
            ->getRepository(event::class)
            ->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tab);
        return new JsonResponse($formatted);


    }


    public function newEventAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $produit = new Event();
        $produit->setTitle($request->get('title'));
        $produit->setLocation($request->get('location'));
        $produit->setDescription($request->get('description'));
        $produit->setType($request->get('type'));
        $produit->setStartDate($request->get('start_date'));
        $produit->setEndDate($request->get('end_date'));
        $produit->setImg($request->get('img'));
        $produit->setNbPlaces($request->get('nbPlaces'));
        $em->persist($produit);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($produit);
        return new JsonResponse($formatted);
    }


}
