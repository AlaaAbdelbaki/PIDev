<?php

namespace ReviewBundle\Controller;

use AppBundle\Entity\review;

use http\Client\Curl\User;
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
            ->add('title',TextType::class)
            ->add('content',TextType::class)
            ->add('User',EntityType::class,array('class'=>'AppBundle:User',
                'choice_label'=>'id'))
            ->add('submit',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();
            return $this->redirectToRoute('affiche');
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
        return $this->redirectToRoute('affiche');
    }
    public function editAction(Request $request, $id)
    {

        $c = $this->getDoctrine()->getRepository(review::class)->find($id);
        $form = $this->createFormBuilder($c)
            ->add('rating',ChoiceType::class,["choices"=>["1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5   ]])
            ->add('title',TextType::class)
            ->add('content',TextType::class)

            ->add('submit',SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($c);
            $em->flush();
            return $this->redirectToRoute('affiche');
        }
        return $this->render("@Review/Default/edit.html.twig", ["formulaire" => $form->createView()]);
    }
}
