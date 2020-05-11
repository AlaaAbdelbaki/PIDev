<?php

namespace BlogBundle\Controller;


use AppBundle\Entity\updates;
use BlogBundle\Form\UpdatesType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;




class UpdatesController extends Controller
{
    public function AjouterAction(Request $request)
    {
        $updates = new updates();
        $form = $this->createForm(\BlogBundle\Form\UpdatesType::class, $updates);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if ($form->isSubmitted()) {
            $file = $updates->getImg();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $fileName);
            $updates->setImg($fileName);
            $em->persist($updates);
            $em->flush();
            return $this->redirectToRoute('afficher_update_admin');
        }
        return $this->render('@Blog/Default/ajouterUpdates.html.twig', array('f' => $form->createView()));
    }
    public function afficheAction()
    {
        $em = $this->getDoctrine();
        $rep = $em->getRepository(updates::class);
        $updatess = $rep->findAll();

        return $this->render('@Blog/Default/afficherUpdates.html.twig', ['updatess' => $updatess]);
    }
    public function affichefrontAction( Request $request)
    {
        $em = $this->getDoctrine();
        $rep = $em->getRepository(updates::class);
        $updatess = $rep->findBy(array(),array('publishDate' => 'DESC'));
        $paginator = $this->get(PaginatorInterface::class);
        $pagination = $paginator->paginate($updatess, $request->query->getInt('page', 1), 5);
        return $this->render('@Blog/Default/afficherUpdatesFront.html.twig', ['updatess' => $pagination]);
    }
    public function modifierAction(Request $request, $id)
    {
        $a = $this->getDoctrine()->getRepository(updates::class)->find($id);
        $form = $this->createForm(UpdatesType::class, $a);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $file = $a->getImg();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $fileName);
            $a->setImg($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($a);
            $em->flush();
            return $this->redirectToRoute('afficher_update_admin');
        }
        return $this->render("@Blog/Default/modififerUpdates.html.twig", ["f" => $form->createView()]);
    }

    public function supprimerAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $a = $em->getRepository(updates::class)->find($id);
        $em->remove($a);
        $em->flush();
        return $this->redirectToRoute('afficher_update_admin');
    }
    public function filterAction(Request $request)
    {
        $requestString = $request->get('Type');

        $updates =  $this->getDoctrine()->getRepository('AppBundle:updates')->findByCategory(['Type'=>$requestString]);

        $paginator = $this->get(PaginatorInterface::class);
        $pagination = $paginator->paginate($updates, $request->query->getInt('page', 1), 5);
        return $this->render('@Blog/Default/afficherUpdatesFront.html.twig', ['updatess' => $pagination]);


    }
}
