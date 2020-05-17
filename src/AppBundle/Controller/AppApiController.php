<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\video;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AppApiController extends Controller

{

    /**
     * @Route("/api/login/{username}/{password}", name="login")
     * @param Request $request
     * @param  $username
     * @param  $password
     * @return Response
     */
    public function loginAction(Request $request, $username, $password){


             $factory= $this->get('security.encoder_factory');
             $user_manager = $this->get('fos_user.user_manager');
             $user = $user_manager->findUserByUsername($username);

        if(!$user){
            return new Response(
                'Username doesnt exists',
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'application/json')
            );
        }
        $encoder= $factory->getEncoder($user);
        $salt = $user->getSalt();
        $match=$encoder->isPasswordValid($user->getPassword(),$password,$salt);
        if(!$encoder->isPasswordValid($user->getPassword(), $password, $salt)) {
            return new Response(
                'Username or Password not valid.',
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'application/json')
            );
        }
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->get('security.token_storage')->setToken($token);
        $this->get('session')->set('_security_main', serialize($token));
        $event = new InteractiveLoginEvent($request, $token);
        $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
        $user=$this->getDoctrine()->getRepository(User::class)->find($user);
        $normalizer = new ObjectNormalizer ();
        $normalizer -> setCircularReferenceHandler ( function ( $user ) {
            return $user -> getId ();
        });
        $serializer = new Serializer([ $normalizer ]);
        $formatted = $serializer->normalize($user , null , [ ObjectNormalizer::ATTRIBUTES => ['id','username','email','roles','birthday','profilePic','sexe','telephoneNumber','adresse','name','firstName','bio']]);

        return new JsonResponse(
            $formatted

        );
    }

    /**
     * @Route("/api/Register/", name="register")
     * @param Request $request

     * @return Response
     */
    public function register(Request $request){
        $email=$request->query->get('email');
        $username=$request->query->get('username');
        $password=$request->query->get('password');
        $userManager = $this->get('fos_user.user_manager');

        // Or you can use the doctrine entity manager if you want instead the fosuser manager
        // to find
        //$em = $this->getDoctrine()->getManager();
        //$usersRepository = $em->getRepository("mybundleuserBundle:User");
        // or use directly the namespace and the name of the class
        // $usersRepository = $em->getRepository("mybundle\userBundle\Entity\User");
        //$email_exist = $usersRepository->findOneBy(array('email' => $email));

        $email_exist = $userManager->findUserByEmail($email);
        $username_exist = $userManager->findUserByUsername($username);

        // Check if the user exists to prevent Integrity constraint violation error in the insertion
        if (($email_exist) or ($username_exist)){
            return new Response(
                'Username or email exists',
                Response::HTTP_UNAUTHORIZED,
                array('Content-type' => 'application/json')
            );
        }

        $user = $userManager->createUser();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setEmailCanonical($email);
        //$user->setLocked(0); // don't lock the user
        $user->setEnabled(1); // enable the user or enable it later with a confirmation token in the email
        // this method will encrypt the password with the default settings :)
        $user->setPlainPassword($password);
        $userManager->updateUser($user);

        return new Response(
            'Welcome '. $user->getUsername(),
            Response::HTTP_OK,
            array('Content-type' => 'application/json')
        );
    }

    /**
     * Lists all participation entities.
     *
     * @Route("/api/Homepage/", name="api_homepage")

     * @return JsonResponse
     */
    public function showAction()
    {

        $videos = $this->getDoctrine()->getRepository(video::class)->findAll();

        $normalizer = new ObjectNormalizer ();

        $normalizer -> setCircularReferenceHandler ( function ( $video ) {
            return $video -> getId ();
        });

        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($videos , null , [ ObjectNormalizer::ATTRIBUTES => ['id','title','url','publishDate','owner'=>['id','username','email','roles','birthday','profilePic','sexe','telephoneNumber','adresse','name','firstName','bio'],
                'votes'=>['id']]]);

        return new JsonResponse($formatted);

    }
    /**
     * @Route("/api/Homepage/ranks", name="ranks_feed_api")

     * @return Response
     */
    public function updateRanksAction()
    {
        $ranks = $this->getDoctrine()->getRepository(video::class)->findRanks();

        $res = array();
        foreach ($ranks as $r) {
            $vid = $this->getDoctrine()->getRepository(video::class)->findById($r['video_id']);

            array_push($res,$vid[0]);

        }

        $normalizer = new ObjectNormalizer ();

        $normalizer -> setCircularReferenceHandler ( function ( $rank ) {
            return $rank -> getId ();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($res , null , [ ObjectNormalizer::ATTRIBUTES => ['id','title','url','publishDate','owner'=>['id','username','email','roles','birthday','profilePic','sexe','telephoneNumber','adresse','name','firstName','bio'],'votes'=>['id']]]);

        return new JsonResponse($formatted);

    }
}
