<?php

namespace EventsBundle\Controller;

use AppBundle\Entity\event;
//use EventsBundle\form\eventType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class EventsController extends Controller
{
    public function addAction(Request $request)
    {
     $event=new event();
     $form=$this->createFormBuilder($event)
         ->add('title',TextType::class)
         ->add('startDate',DateType::class)
         ->add('endDate',DateType::class)
         ->add('img', FileType::class, array('data_class'=>null, 'required'=>false))
         ->add('location',TextType::class)
         ->add('nb_places',NumberType::class)
         ->add('description',TextType::class)
         ->add('type',ChoiceType::class,["choices"=>["audition"=>"audition","casting"=>"casting","offre emploi"=>"offre emploi","concert"=>"concert"]])
         ->add('Submit', SubmitType::class)
         ->getForm();
     $form->handleRequest($request);
     if($form->isSubmitted() && $form->isValid())
     {
         $em = $this->getDoctrine()->getManager();
         $em->persist($event);
         $em->flush();

     }
     return($this->render("@Events/event/event_add.html.twig",['f'=>$form->createView()]));


    }


    public function afficheAction()
    {
        $tab=$this->getDoctrine()->getRepository(event::class)->findAll();

        return $this->render('@Events/event/affiche.html.twig',array('t'=>$tab));
    }


    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $event =$em->getRepository(event::class)->find($id);
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('affiche');
    }

    public function modifierAction(Request $request,$id){
        $event=$this->getDoctrine()->getRepository(event::class)->find($id);


        $form=$this->createFormBuilder($event)
            ->add('title',TextType::class)
            ->add('startDate',DateType::class)
            ->add('endDate',DateType::class)
            ->add('img', FileType::class, array('data_class'=>null, 'required'=>false))
            ->add('location',TextType::class)
            ->add('nb_places',NumberType::class)
            ->add('description',TextType::class)
            ->add('type',ChoiceType::class,["choices"=>["audition"=>"audition","casting"=>"casting","offre emploi"=>"offre emploi","concert"=>"concert"]])
            ->add('Submit', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $conn = $this->getDoctrine()->getManager();
            $conn->persist($event);
            $conn->flush();

            return $this->redirect($this->generateUrl('affiche'));
        }

        return $this->render('@EventsBundle\Resources\views\event\modifier.html.twig',['event'=>$event,'f'=>$form->createView()]);

    }
}
