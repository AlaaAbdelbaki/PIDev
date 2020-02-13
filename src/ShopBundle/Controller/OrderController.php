<?php

namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\orders;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends Controller
{
    public function afficherOrderAction(){
        $em=$this->getDoctrine();
        $rep=$em->getRepository(orders::class);
        $orders=$rep->findAll();

        return $this->render('@Shop/Default/admin_order.html.twig',['orders'=>$orders]);
    }
    public function supprimerOrderAction($id){
        $em=$this->getDoctrine()->getManager();
        $product=$em->getRepository(orders::class)->find($id);
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('shop_adminorder');
    }
    public function modifierOrderAction(Request $request,$id){
        $product=$this->getDoctrine()->getRepository(orders::class)->find($id);

        $form=$this->createFormBuilder($product)
            ->add('orderDate',DateType::class)
            ->add('total',TextType::class)
            ->add('address',TextType::class)
            ->add('modifier',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $conn = $this->getDoctrine()->getManager();
            $conn->persist($product);
            $conn->flush();

            return $this->redirect($this->generateUrl('shop_adminorder'));
        }

        return $this->render('@Shop/Default/admin_order_update.html.twig',['product'=>$product,'f'=>$form->createView()]);


    }

    public function ajouterOrderAction(Request $request){
        $orders=new orders();

        $form=$this->createFormBuilder($orders)
            ->add('orderDate',DateType::class)
            ->add('total',TextType::class)
            ->add('address',TextType::class)
            ->add('Add',SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $conn = $this->getDoctrine()->getManager();
            $conn->persist($orders);
            $conn->flush();

            return $this->redirect($this->generateUrl('shop_adminorder'));
        }
        return $this->render('@Shop/Default/admin_order_add.html.twig',['f'=>$form->createView()]);
    }

}
