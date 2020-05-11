<?php

namespace EventsBundle\Controller;

use AppBundle\Entity\event;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EventsBundle:Default:index.html.twig');
    }

    public function afficheAction()
    {
        $tab=$this->getDoctrine()->getRepository(event::class)->findAll();

        return $this->render('@Events/Default/affiche.html.twig',array('t'=>$tab));
    }
}
