<?php

namespace ReviewBundle\Controller;

use AppBundle\Entity\complaint;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ComplaintApiController extends Controller
{
    public function allComplaintAction()
    {

        $tasks=$this->getDoctrine()->getManager()
            ->getRepository(complaint::class)
            ->findAll();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formated=$serializer->normalize($tasks);
        return new JsonResponse($formated);
    }
    public function AjouterCJsonAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $complaint = new complaint();
        $complaint->setSubject($request->get('subject'));
        $complaint->setContent($request->get('content'));
        $complaint->setUser($this->getUser());
        $em->persist($complaint);
        $em->flush();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($complaint);
        return new JsonResponse($formatted);
}}
