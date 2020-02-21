<?php

namespace ReviewBundle\Controller;

use AppBundle\Entity\article;
use AppBundle\Entity\competition;
use AppBundle\Entity\complaint;
use AppBundle\Entity\event;
use AppBundle\Entity\orders;
use AppBundle\Entity\review;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class ReviewUseerController extends Controller
{
    public function AddReviewUserAction(Request $request){
        $review = new review();
        $form = $this->createFormBuilder($review)
            ->add('rating',ChoiceType::class,["choices"=>["1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5   ]])
            ->add('category',ChoiceType::class,["choices"=>["Events"=>event::class,"Orders"=>orders::class
                ,"Competition"=>competition::class,"Articles"=>article::class]])
            ->add('title',TextType::class)
            ->add('content',TextType::class)

            ->add('submit',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        { $review->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();
            //return $this->redirectToRoute('affiche');
        }
        return $this->render("@Review/Default/ajout_review_user.html.twig",array('form'=>$form->createView()));
    }

}
