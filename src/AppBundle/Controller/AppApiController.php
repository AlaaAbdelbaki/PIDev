<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
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


}
