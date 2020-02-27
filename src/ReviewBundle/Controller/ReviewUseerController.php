<?php

namespace ReviewBundle\Controller;

use AppBundle\Entity\article;
use AppBundle\Entity\competition;
use AppBundle\Entity\complaint;
use AppBundle\Entity\event;
use AppBundle\Entity\orders;
use AppBundle\Entity\review;
use blackknight467\StarRatingBundle\Form\RatingType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class ReviewUseerController extends Controller
{
    public function AddReviewUserAction(Request $request){
        $review = new review();
        $form = $this->createFormBuilder($review)
            ->add('rating', RatingType::class, [
                'stars' => 5

            ])
            ->add('category',ChoiceType::class,["choices"=>["Events"=>"Events","Orders"=>"Orders"
                ,"Competition"=>"Competition","Articles"=>"Articles"]])
            ->add('title',TextType::class)
            ->add('content',TextareaType::class)

            ->add('submit',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        { $review->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render("@Review/Default/ajout_review_user.html.twig",array('form'=>$form->createView()));
    }

}
