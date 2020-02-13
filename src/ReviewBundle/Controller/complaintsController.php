<?php

namespace ReviewBundle\Controller;

use AppBundle\Entity\complaint;
use AppBundle\Entity\review;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class complaintsController extends Controller
{
    public function AddReclamAction(Request $request){
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
            return $this->redirectToRoute('affiche_reclam');
        }
        return $this->render("@Review/Default/ajout_reclam.html.twig",array('f'=>$form->createView()));
    }
    public function affiche_reclamAction()
    {
        $tab=$this->getDoctrine()->getRepository(complaint::class)->findAll();

        return $this->render("@Review/Default/reclam.html.twig",array('r'=>$tab));
    }
    public function delete_reclamAction($id){
        $em=$this->getDoctrine()->getManager();
        $reclam=$em->getRepository(complaint::class)->find($id);
        $em->remove($reclam);
        $em->flush();
        return $this->redirectToRoute('affiche_reclam');
    }
    public function edit_reclamAction(Request $request, $id)
    {

        $d = $this->getDoctrine()->getRepository(complaint::class)->find($id);
        $form = $this->createFormBuilder($d)
            ->add('subject',TextType::class)
            ->add('content',TextType::class)
            ->add('submit',SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($d);
            $em->flush();
            return $this->redirectToRoute('affiche_reclam');
        }
        return $this->render("@Review/Default/edit_reclam.html.twig", ["form" => $form->createView()]);
    }
}
