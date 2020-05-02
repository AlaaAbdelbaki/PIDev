<?php

namespace TalentBundle\Controller;

use AppBundle\Entity\subscription;
use AppBundle\Entity\User;
use AppBundle\Entity\video;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TalentController extends Controller
{
    public function viewProfileDetailsAction()
    {
        return $this->render("@Talent/Main/profile_details.html.twig");
    }
    public function updateProfileAction(Request $request)
    {
        $user = $this->getUser();
        $pp=$user->getProfilePic();
        $form = $this->createFormBuilder($user)
            ->add("email", EmailType::class)
            ->add("birthday", BirthdayType::class)
            ->add('profile_pic', FileType::class, ['data_class' => null, 'required' => false])
            ->add("sexe", ChoiceType::class, ["choices" => ["Homme" => "male", "Femme" => "female"]])
            ->add("telephoneNumber", NumberType::class)
            ->add("adresse", TextType::class)
            ->add("name", TextType::class)
            ->add("first_name", TextType::class)
            ->add("bio", TextareaType::class)
            ->add("submit", SubmitType::class, ["label" => "Mettre à jour"])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $user->getProfilePic();
            if ($file != null) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('image_directory'), $fileName);
                $user->setProfilePic($fileName);
            } else {
                $user->setProfilePic($pp);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('profile_details');
        }
        return $this->render('@Talent/Main/edit_profile.html.twig', ["f" => $form->createView()]);
    }

    public function listAction()
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();
        return $this->render('@Talent/Dashboard/list_users.html.twig', ["users" => $user]);
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('list_profile');
    }

    public function addAction(Request $request)
    {
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add("email", EmailType::class)
            ->add("birthday", BirthdayType::class)
            ->add('profile_pic', FileType::class, ['data_class' => null, 'required' => false])
            ->add("sexe", ChoiceType::class, ["choices" => ["Homme" => "male", "Femme" => "female"]])
            ->add("telephoneNumber", NumberType::class)
            ->add("adresse", TextType::class)
            ->add("name", TextType::class)
            ->add("first_name", TextType::class)
            ->add("bio", TextType::class)
            ->add("submit", SubmitType::class, ["label" => "Ajouter un utilisateur"])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $user->getProfilePic();
            if ($file != null) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('image_directory'), $fileName);
                $user->setProfilePic($fileName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('list_profile');
        }
        return $this->render('@Talent/Dashboard/add_user.html.twig', ["f" => $form->createView()]);
    }


    public function userProfileAction($username)
    {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(["username" => $username]);
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findBy(["username" => $username]);
        if ($user != null) {
            $id = $user->getId();
            $loggedIn = $this->getUser()->getId();
            $subscription = $this->getDoctrine()->getRepository(subscription::class)->exists($id, $loggedIn);
            if ($subscription == null) {
                $state = false;
            } else {
                $state = true;
            }
//            var_dump($state);
            $subcount = $this->getDoctrine()->getManager()->getRepository(subscription::class)->getSubscribersCount($id);
            $subscribedto = $this->getDoctrine()->getManager()->getRepository(subscription::class)->getSubscribtionCount($user->getId());
            //        var_dump($id);
            $video = $this->getDoctrine()->getManager()->getRepository(video::class)->findBy(["owner" => $id]);
            return $this->render("@Talent/Main/profile.html.twig", ["users" => $users, "videos" => $video, "subcount" => $subcount, "subbedto" => $subscribedto, "subscribtion" => $state]);
        } else {
            return $this->render("@Talent/Default/error.html.twig");
        }
    }

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $user = $em->getRepository('AppBundle:User')->findEntitiesByString($requestString);
        if (!$user) {
            $result['user']['error'] = "Utilisateur inexistant :( ";
        } else {
            $result['user'] = $this->getRealEntities($user);
        }
        return new Response(json_encode($result));
    }


    public function getRealEntities($user)
    {
        foreach ($user as $user) {
            $realEntities[$user->getId()] = [$user->getProfilePic(), $user->getUsername()];

        }
        return $realEntities;
    }

    public function subscribeAction(Request $request, $id)
    {
        $url = $request->headers->get('referer');
        $user = $this->getUser();
        $sub = new subscription();
        $date = new \DateTime();
        $sub->setSub($user);
        $sub->setSubedto($this->getDoctrine()->getRepository(User::class)->find($id));
        $sub->setSubscriptionDate($date);
        $subscription = $this->getDoctrine()->getRepository(subscription::class)->exists($id, $user->getId());
        if ($user->getId() != $id && $subscription == null) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sub);
            $em->flush();
        }

        return $this->redirect($url);
    }

    public function unsubscribeAction(Request $request,$id)
    {
        $url = $request->headers->get('referer');
        $user = $this->getUser()->getId();
        $sub = $this->getDoctrine()->getManager()->getRepository(subscription::class);
        if($sub->exists($id,$user) != null)
        {
            $sub->unsub($id,$user);
        }
        return $this->redirect($url);
    }

    public function changePasswordAction($token){

    }


}
