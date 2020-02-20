<?php

namespace ShopBundle\Controller;

use AppBundle\Entity\product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProduitController extends Controller
{
    public function afficherProduitAction(){
        $em=$this->getDoctrine();
        $rep=$em->getRepository(product::class);
        $products=$rep->findAll();

        return $this->render('@Shop/Default/admin_shop.html.twig',['products'=>$products]);
    }
    public function supprimerProduitAction($_id){
        $em=$this->getDoctrine()->getManager();
        $product=$em->getRepository(product::class)->find($_id);
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('shop_admin');
    }
    public function modifierProduitAction(Request $request,$_id){
        $product=$this->getDoctrine()->getRepository(product::class)->find($_id);


        $form=$this->createFormBuilder($product)
            ->add('productName',TextType::class)
            ->add('img',FileType::class,array('data_class'=>null ,'required'=>false))
            ->add('stock',TextType::class)
            ->add('price',TextType::class)
            ->add('Modifier',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $file = $product->getImg();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $fileName);
            $product->setImg($fileName);
            $conn = $this->getDoctrine()->getManager();
            $conn->persist($product);
            $conn->flush();

            return $this->redirect($this->generateUrl('shop_admin'));
        }

        return $this->render('@Shop/Default/admin_shop_update.html.twig',['product'=>$product,'f'=>$form->createView()]);

    }

    public function ajouterProduitAction(Request $request){
        $product=new product();
        $form=$this->createFormBuilder($product)
            ->add('productName',TextType::class)
            ->add('img',FileType::class,array('data_class'=>null ,'required'=>false))
            ->add('stock',TextType::class)
            ->add('price',TextType::class)
            ->add('ajouter',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $file = $product->getImg();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $fileName);
            $product->setImg($fileName);
            $conn = $this->getDoctrine()->getManager();
            $conn->persist($product);
            $conn->flush();

            return $this->redirect($this->generateUrl('shop_admin'));
        }
        return $this->render('@Shop/Default/admin_shop_add.html.twig',['f'=>$form->createView()]);
    }
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $product =  $em->getRepository('AppBundle:product')->findEntitiesByString($requestString);
        if(!$product) {
            $result['product']['error'] = "User Not found :( ";
        } else {
            $result['product'] = $this->getRealEntities($product);
        }
        return new Response(json_encode($result));
    }


    function getRealEntities($product)
    {
        foreach ($product as $product){
            $realEntities[$product->getId()] = [$product->getImg(),$product->getProductName()];

        }
        return $realEntities;
    }

}
