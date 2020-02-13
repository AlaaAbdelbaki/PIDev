<?php

namespace BlogBundle\Controller;

use AppBundle\Entity\article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\BlogType;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    public function AddAction(Request $request)
    {
        $article = new article();
        $form = $this->createForm(\BlogBundle\Form\BlogType::class, $article);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted()) {
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('afficher');
        }


        return $this->render('@Blog/Default/addArticle.html.twig', array('f' => $form->createView()));
    }

    public function afficherAction()
    {
        $em = $this->getDoctrine();
        $rep = $em->getRepository(article::class);
        $articles = $rep->findAll();

        return $this->render('@Blog/Default/afficherArticle.html.twig', ['articles' => $articles]);
    }

    public function editAction(Request $request, $id)
    {
        $a = $this->getDoctrine()->getRepository(article::class)->find($id);
        $form = $this->createForm(BlogType::class, $a);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($a);
            $em->flush();
             return $this->redirectToRoute('afficher');
        }
        return $this->render("@Blog/Default/editArticle.html.twig", ["form" => $form->createView()]);
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $a = $em->getRepository(article::class)->find($id);
        $em->remove($a);
        $em->flush();
      return $this->redirectToRoute('afficher');
    }
}