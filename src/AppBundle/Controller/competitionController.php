<?php

namespace AppBundle\Controller;

use AppBundle\Entity\competition;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Competition controller.
 *
 * @Route("competition")
 */
class competitionController extends Controller
{
    /**
     * Lists all competition entities.
     *
     * @Route("/", name="competition_index")
     * @Security("has_role('ROLE_USER')")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $competitions = $em->getRepository('AppBundle:competition')->findAll();

        return $this->render('competition/index.html.twig', array(
            'competitions' => $competitions,
        ));
    }

    /**
     * Finds and displays a competition entity.
     *
     * @Route("/{id}", name="competition_show")
     * @Method("GET")
     */
    public function showAction(competition $competition)
    {

        return $this->render('competition/show.html.twig', array(
            'competition' => $competition,
        ));
    }
}
