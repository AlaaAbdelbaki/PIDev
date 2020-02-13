<?php

namespace TalentBundle\Controller;


use AppBundle\Entity\video;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;

class VideoController extends Controller
{
    public function addVideoAction(Request $request)
    {
        $video = new video();
        $form = $this->createFormBuilder($video)
            ->add('url',TextType::class)
            ->add('title',TextType::class)
            ->add('publishDate',DateType::class)
            ->add('submit',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();
            return $this->redirectToRoute('list_video');
        }
        return $this->render("@Talent/Backend/add_video.html.twig",["f"=>$form->createView()]);
    }


    public function viewVideoAction()
    {
        $video = $this->getDoctrine()->getManager()->getRepository(video::class)->findAll();
        return $this->render("@Talent/Backend/list_videos.html.twig",["videos"=>$video]);
    }
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $video =$em->getRepository(video::class)->find($id);
        $em->remove($video);
        $em->flush();
        return $this->redirectToRoute('list_video');
    }


    public function updateAction(Request $request,$id)
    {
        $video = $this->getDoctrine()->getRepository(video::class)->find($id);


        $form = $this->createFormBuilder($video)
            ->add('url',TextType::class)
            ->add('title',TextType::class)
            ->add('publishDate',DateType::class)
            ->add('submit',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();

            return $this->redirectToRoute('list_video');
        }

        return $this->render('@Talent/Backend/update_video.html.twig',['videos'=>$video,'f'=>$form->createView()]);

    }
}
