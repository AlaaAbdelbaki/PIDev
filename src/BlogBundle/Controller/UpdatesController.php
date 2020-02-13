<?php

namespace BlogBundle\Controller;


use AppBundle\Entity\updates;
use BlogBundle\Form\UpdatesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;




class UpdatesController extends Controller
{
    public function AjouterAction(Request $request)
    {
        $updates = new updates();
        $form = $this->createForm(\BlogBundle\Form\UpdatesType::class, $updates);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted()) {
            $em->persist($updates);
            $em->flush();
            return $this->redirectToRoute('affiche');
        }


        return $this->render('@Blog/Default/ajouterUpdates.html.twig', array('f' => $form->createView()));
    }
    public function afficheAction()
    {
        $em = $this->getDoctrine();
        $rep = $em->getRepository(updates::class);
        $updatess = $rep->findAll();

        return $this->render('@Blog/Default/afficherUpdates.html.twig', ['updatess' => $updatess]);
    }
    public function modifierAction(Request $request, $id)
    {
        $a = $this->getDoctrine()->getRepository(updates::class)->find($id);
        $form = $this->createForm(UpdatesType::class, $a);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($a);
            $em->flush();
            return $this->redirectToRoute('affiche');
        }
        return $this->render("@Blog/Default/modififerUpdates.html.twig", ["form" => $form->createView()]);
    }

    public function supprimerAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $a = $em->getRepository(updates::class)->find($id);
        $em->remove($a);
        $em->flush();
        return $this->redirectToRoute('affiche');
    }

}
