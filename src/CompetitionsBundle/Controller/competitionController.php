<?php

namespace CompetitionsBundle\Controller;

use AppBundle\Entity\competition;
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


}
