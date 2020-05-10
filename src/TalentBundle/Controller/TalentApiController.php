<?php

namespace TalentBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TalentApiController extends Controller
{
    public function updateProfileAction(Request $request)
    {
        $id = $request->get('id');
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
//        var_dump($request->get('firstName'));
        if($request->get('firstName') != "null")
        {
            $user->setFirstName($request->get('firstName'));
        }
        if($request->get('lastName')!="null")
        {
            $user->setName($request->get('lastName'));
        }
        if($request->get('bio')!="null")
        {
            $user->setBio($request->get('bio'));
        }
        if($request->get('gender')!="null")
        {
            $user->setSexe($request->get('gender'));
        }
        if($request->get('phoneNumber')!="null")
        {
            $user->setTelephoneNumber($request->get('phoneNumber'));
        }
        if($request->get('email')!="null")
        {
            $user->setEmail($request->get('email'));
        }
        if($request->get('password')!="null")
        {
            $user->setPassword($request->get('password'));
        }
        if($request->get('address')!="null")
        {
            $user->setAdresse($request->get('address'));
        }
        if($request->get('birthday')!="null")
        {
            $date=date_create_from_format('Y-m-d',date('Y-m-d',strtotime($request->get('birthday'))));
//            var_dump($date);
//            var_dump($request->get('birthday'));
            $user->setBirthday($date);
        }
//        var_dump($request->get('birthday'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $normalizer = new ObjectNormalizer();
        $normalizer = $normalizer->setCircularReferenceHandler(function ($user)
        {
            return $user->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($user);
        return new JsonResponse($formatted);

    }

    public function returnUserAction(Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));
        $normalizer = new ObjectNormalizer();
        $normalizer = $normalizer->setCircularReferenceHandler(function ($user){
            return $user->getId();
        });
        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($user , null , [ ObjectNormalizer::ATTRIBUTES => ['id','username','email','roles','birthday','profilePic','sexe','telephoneNumber','adresse','name','firstName','bio']]);
        return new JsonResponse($formatted);
    }

    public function deleteUserAction(Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return new JsonResponse();
    }

    public function uploadImageAction($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        $temp = explode(".", $_FILES["file"]["name"]);
        $extension = end($temp);

        if ((($_FILES["file"]["type"] == "image/gif") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/pjpeg") || ($_FILES["file"]["type"] == "image/x-png") || ($_FILES["file"]["type"] == "image/png")) && ($_FILES["file"]["size"] < 5000000) && in_array($extension, $allowedExts)) {
            if ($_FILES["file"]["error"] > 0) {
                $named_array = array("Response" => array(array("Status" => "error")));
                echo json_encode($named_array);
            } else {
                move_uploaded_file($_FILES["file"]["tmp_name"], "D:\Programming\Web\htdocs\annee_2019_2020\PIDev\web\assets\uploads\\" . $_FILES["file"]["name"]);
                $user->setProfilePic($_FILES["file"]["name"]);
                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();

//                $Path = $_FILES["file"]["name"];
//                $named_array = array("Response" => array(array("Status" => "ok")));
//                echo json_encode($named_array);
            }
        }
//        else {
//            $named_array = array("Response" => array(array("Status" => "invalid")));
//            echo json_encode($named_array);
//        }
        return new JsonResponse();
    }


}
