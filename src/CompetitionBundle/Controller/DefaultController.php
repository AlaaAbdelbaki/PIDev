<?php

namespace CompetitionBundle\Controller;
use AppBundle\Entity\competition;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
/**
 * Competition controller.
 *
 * @Route("competitions")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="competitions_index")
     *  @Security("has_role('ROLE_USER')")
     * @Method("GET")
     */
    public function indexAction()
    {  $em = $this->getDoctrine()->getManager();
        $competitions = $em->getRepository('AppBundle:competition')->findAll();
        return $this->render('CompetitionBundle:Default:index.html.twig', array(
            'competitions' => $competitions,
        ));
    }
    /**
     * @Route("/admin/Competitions")
     */
    public function admionindexAction()
    {
        return $this->render('CompetitionBundle:Default:admin_index.html.twig');
    }
}
