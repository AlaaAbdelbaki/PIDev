<?php

namespace EventsBundle\Controller;

use AppBundle\Entity\event;
use AppBundle\Entity\ticket;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;



class EventsApiController extends Controller
{
    public function afficheEventApiAction()
    {
        $tab = $this->getDoctrine()->getManager()
            ->getRepository(event::class)
            ->findAll();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });


        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($tab);
        return new JsonResponse($formatted);


       /* $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tab);
        return new JsonResponse($formatted);*/
    }


    public function buyTicketApiAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(event::class)->find($id);
        $event->setnbPlaces($event->getnbPlaces() - 1);
        $em->persist($event);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($event);
        return new JsonResponse($formatted);
    }




/*
    public function AfficheTicketApiAction()
    {
        $tab=$this->getDoctrine()->getRepository(ticket::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($tab);
        return new JsonResponse($formatted);
    }
*/



}

