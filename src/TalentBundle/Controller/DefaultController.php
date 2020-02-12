<?php

namespace TalentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TalentBundle:Default:index.html.twig');
    }
}
