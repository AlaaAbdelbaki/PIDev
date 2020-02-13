<?php

namespace EventsBundle\Controller;

use AppBundle\Entity\event;
use AppBundle\Entity\ticket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ticketController extends Controller
{
public function ajoutAction(Request $request)
{
    $ticket=new ticket();
    $form =$this->createFormBuilder($ticket)
        ->add('price',TextType::class)
//        ->add('event',EntityType::class)
        ->add('Submit',SubmitType::class)
        ->getForm();
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
        $em=$this->getDoctrine()->getManager();
        $em->persist($ticket);
        $em->flush();
    }
    return($this->render("@Events/ticket/ticket_add.html.twig",['t'=>$form->createView()]));
}

//    public function listAction()
//    {
//        $tab=$this->getDoctrine()->getRepository(ticket::class)->findAll();
//
//        return $this->render('@Events/ticket/affiche.html.twig',array('t'=>$tab));
//    }
//
//    public function suppAction($id)
//    {
//        $em = $this->getDoctrine()->getManager();
//        $ticket =$em->getRepository(ticket::class)->find($id);
//        $em->remove($ticket);
//        $em->flush();
//        return $this->redirectToRoute('list');
//    }
//
//    public function editAction(Request $request,$id){
//        $ticket=$this->getDoctrine()->getRepository(event::class)->find($id);
//
//
//        $form=$this->createFormBuilder($ticket)
//            ->add('price',NumberType::class)
////            ->add('event_id',NumberType::class)
//            ->add('Submit', SubmitType::class)
//            ->getForm();
//        $form->handleRequest($request);
//
//        if($form->isSubmitted() && $form->isValid()){
//            $conn = $this->getDoctrine()->getManager();
//            $conn->persist($ticket);
//            $conn->flush();
//
//            return $this->redirect($this->generateUrl('list'));
//        }
//
//        return $this->render('@EventsBundle\Resources\views\ticket\modifier.html.twig',['event'=>$ticket,'f'=>$form->createView()]);
//
//    }

}
