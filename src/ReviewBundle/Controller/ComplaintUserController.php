<?php

namespace ReviewBundle\Controller;

use AppBundle\Entity\complaint;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class ComplaintUserController extends Controller
{
    public function AddReclamActionUser(Request $request){
        $complaint = new complaint();
        $form = $this->createFormBuilder($complaint)
            ->add('subject',TextType::class)
            ->add('content',TextType::class)
            ->add('submit',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($complaint);
            $em->flush();
            //return $this->redirectToRoute('affiche_reclam');
        }
        return $this->render("@Review/Default/ajout_reclam_user.html.twig",array('f'=>$form->createView()));
    }
}
