<?php

namespace TalentBundle\Controller;


use AppBundle\Entity\User;
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
        $link = "https://www.youtube.com/embed/";
        $user = $this->getUser();
        $date = new \DateTime();
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
            $url = $video->getUrl();
            $link = $link.substr($url,-11);
            $video->setUrl($link);
            $video->setPermalink('http://127.0.0.1:8000/');
            $video->setCommentable(true);
            $video->setPublishDate($date);
            $video->setOwner($user);
            $em->persist($video);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render("@Talent/Main/add_video.html.twig",["f"=>$form->createView()]);
    }


    public function viewVideoAction($id)
    {
        $video = $this->getDoctrine()->getManager()->getRepository(video::class)->findByOwner($id);
        return $this->render("@Talent/Dashboard/list_videos.html.twig",["videos"=>$video]);
    }


    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $video =$em->getRepository(video::class)->find($id);
        $em->remove($video);
        $em->flush();
        return $this->redirectToRoute('user_profile',['id' =>$this->getUser()->getId()]);
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

        return $this->render('@Talent/Dashboard/update_video.html.twig',['videos'=>$video,'f'=>$form->createView()]);

    }

    public function viewAction()
    {
        $video = $this->getDoctrine()->getManager()->getRepository(video::class)->findAll();
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        return $this->render("@Talent/Main/videos.html.twig",["videos"=>$video,"users"=>$user]);
    }


    public function videoDetailsAction($id)
    {
        $video = $this->getDoctrine()->getManager()->getRepository(video::class)->findOneBy(["id"=>$id]);
//        var_dump($video);
        return $this->render("@Talent/Main/video_details.html.twig",["videos"=>$video]);
    }
}
