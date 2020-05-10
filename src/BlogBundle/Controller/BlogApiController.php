<?php

namespace BlogBundle\Controller;

use AppBundle\Entity\article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;

class BlogApiController extends Controller
{
    public function showAllArticlesMobileAction(){
        $em=$this->getDoctrine();
        $rep=$em->getRepository(article::class);
        $articles=$rep->findAll();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });


        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($articles);
        return new JsonResponse($formatted);

    }
}
