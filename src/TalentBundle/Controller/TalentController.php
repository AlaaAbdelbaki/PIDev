<?php

namespace TalentBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class TalentController extends Controller
{
    public function viewProfileAction()
    {
        return $this->render("@Talent/Frontend/profile.html.twig");
    }
    public function updateProfileAction(Request $request,$id)
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($id);
        $form = $this->createFormBuilder($user)
            ->add("email",EmailType::class)
            ->add("birthday",BirthdayType::class)
//            ->add('profile_pic',FileType::class,array('labesl'=>'insert Image'))
            ->add("sexe",ChoiceType::class,["choices"=>["Homme"=>"male","Femme"=>"female"]])
            ->add("telephoneNumber",NumberType::class)
            ->add("adresse",TextType::class)
            ->add("name",TextType::class)
            ->add("first_name",TextType::class)
            ->add("bio",TextType::class)
            ->add("submit",SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
//            $file = $user->getImg();
//            $fileName = md5(uniqid()).'.'.$file->guessExtension();
//            $file->move($this->getParameter('photos_directory'), $fileName);
//            $user->setImage($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('user_profile');
        }
        return $this->render('@Talent/Frontend/edit_profile.html.twig',["f"=>$form->createView()]);
    }
}
