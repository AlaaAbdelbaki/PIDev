<?php

namespace EventsBundle\Controller;

use AppBundle\Entity\event;
use AppBundle\Entity\ticket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ticketController extends Controller
{
public function ajoutAction(Request $request ,$id)
{
    $ticket=new ticket();
    $ticket->setEvent($this->getDoctrine()->getRepository(event::class)->find($id));
    $form =$this->createFormBuilder($ticket)
        ->add('price',TextType::class)
        ->add('event',EntityType::class,array('class'=>'AppBundle:event',
            'choice_label'=>'title',
            'disabled'=>'disabled'))
        ->add('Submit',SubmitType::class)
        ->getForm();
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid())
    {
        $em=$this->getDoctrine()->getManager();
        $em->persist($ticket);
        $em->flush();
        return $this->redirectToRoute('show_events_admin');
    }
    return($this->render("@Events/ticket/ticket_add.html.twig",['t'=>$form->createView()]));
}

    public function listAction()
    {
        $tab=$this->getDoctrine()->getRepository(ticket::class)->findAll();
        return $this->render('@Events/ticket/affiche.html.twig',array('t'=>$tab));
    }

    public function suppAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $ticket =$em->getRepository(ticket::class)->find($id);
        $em->remove($ticket);
        $em->flush();
        return $this->redirectToRoute('show_events_admin');
    }

    public function editAction(Request $request,$id){
        $ticket=$this->getDoctrine()->getRepository(ticket::class)->find($id);


        $form=$this->createFormBuilder($ticket)
            ->add('price',NumberType::class)
            ->add('event',EntityType::class,array('class'=>'AppBundle:event',
                'choice_label'=>'title'))
            ->add('Submit', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $conn = $this->getDoctrine()->getManager();
            $conn->persist($ticket);
            $conn->flush();

            return $this->redirect($this->generateUrl('show_events_admin'));
        }

        return $this->render('@Events/ticket/edit.html.twig',['event'=>$ticket,'f'=>$form->createView()]);

    }
    public function buyTicketAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $event =$em->getRepository(event::class)->find($id);
        $event->setnbPlaces($event->getnbPlaces()-1);
        $em->persist($event);
        $em->flush();

        $snappy = $this->get('knp_snappy.pdf');
        $html = $this->renderView('@Events/ticket/ticket_pdf.html.twig', array(
            'event'=>$event
        ));

        $filename = 'myFirstSnappyPDF';

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );


    }

}
