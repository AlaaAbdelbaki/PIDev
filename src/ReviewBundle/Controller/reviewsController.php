<?php

namespace ReviewBundle\Controller;

use AppBundle\Entity\article;
use AppBundle\Entity\competition;
use AppBundle\Entity\event;
use AppBundle\Entity\orders;
use AppBundle\Entity\review;

use AppBundle\Repository\reviewRepository;
use http\Client\Curl\User;
use ReviewBundle\ReviewBundle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class reviewsController extends Controller
{
    public function AddReviewAction(Request $request){
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
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();
            return $this->redirectToRoute('review_show');
        }
        return $this->render("@Review/Default/ajout_review.html.twig",array('form'=>$form->createView()));
    }

    public function afficheAction()
    {
        $tab=$this->getDoctrine()->getRepository(review::class)->findAll();

        return $this->render("@Review/Default/index.html.twig",array('formulaire'=>$tab));
    }
    public function deleteAction($id){
        $em=$this->getDoctrine()->getManager();
        $review=$em->getRepository(review::class)->find($id);
        $em->remove($review);
        $em->flush();
        return $this->redirectToRoute('review_show');
    }
    public function editAction(Request $request, $id)
    {

        $c = $this->getDoctrine()->getRepository(review::class)->find($id);
        $form = $this->createFormBuilder($c)
            ->add('rating',ChoiceType::class,["choices"=>["1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5   ]])
            ->add('category',ChoiceType::class,["choices"=>["Events"=>event,"Orders"=>orders
                ,"Competition"=>competition,"Articles"=>article]])
            ->add('title',TextType::class)
            ->add('content',TextType::class)
            ->add('submit',SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($c);
            $em->flush();
            return $this->redirectToRoute('review_show');
        }
        return $this->render("@Review/Default/edit.html.twig", ["f" => $form->createView()]);
    }


}
