<?php

namespace ReviewBundle\Controller;

use AppBundle\Entity\complaint;
use AppBundle\Entity\Message;
use AppBundle\Entity\review;
use ReviewBundle\ReviewBundle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class complaintsController extends Controller
{
    public function AddReclamAction(Request $request)
    {
        $complaint = new complaint();
        $form = $this->createFormBuilder($complaint)
            ->add('subject', TextType::class)
            ->add('content', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($complaint);
            $em->flush();
            return $this->redirectToRoute('affiche_reclam');
        }
        return $this->render("@Review/Default/ajout_reclam.html.twig", array('f' => $form->createView()));
    }

    public function affiche_reclamAction()
    {
        $tab = $this->getDoctrine()->getRepository(complaint::class)->findAll();

        return $this->render("@Review/Default/reclam.html.twig", array('r' => $tab));
    }

    public function delete_reclamAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reclam = $em->getRepository(complaint::class)->find($id);
        $em->remove($reclam);
        $em->flush();
        return $this->redirectToRoute('affiche_reclam');
    }

    public function edit_reclamAction(Request $request, $id)
    {

        $d = $this->getDoctrine()->getRepository(complaint::class)->find($id);
        $form = $this->createFormBuilder($d)
            ->add('subject', TextType::class)
            ->add('content', TextType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($d);
            $em->flush();
            return $this->redirectToRoute('affiche_reclam');
        }
        return $this->render("@Review/Default/edit_reclam.html.twig", ["form" => $form->createView()]);
    }


    public function sendMessageAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $complaint = $em->getRepository('AppBundle:complaint')->find($id);
        $msg = new Message();
        $form = $this->createFormBuilder($msg)
             ->add ('contenu', TextAreaType::class)


        ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $complaint->getUser();
            $message = (new \Swift_Message('Hello Email'))
                ->setFrom('zribisarahzribi@gmail.com')
                ->setTo($user->getEmailCanonical())
                ->setBody($msg->getContenu())
            /*
                 * If you also want to include a plaintext version of the message
                ->addPart(
                    $this->renderView(
                        'Emails/registration.txt.twig',
                        array('name' => $name)
                    ),
                    'text/plain'
                )
                */
            ;

            $this->get('mailer')->send($message);
            $reclam = $em->getRepository(complaint::class)->find($id);
            $em->remove($reclam);
            $em->flush();
            $this->redirectToRoute("affiche_reclam");

        }
        return $this->render("@Review/Default/form.html.twig",array('r'=> $complaint ,'form' => $form->createView() ));
    }
}



