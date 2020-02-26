<?php

namespace ShopBundle\Controller;

use AppBundle\Entity\product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller
{
    public function indexAction()
    {
        return $this->render('ShopBundle:Default:index.html.twig');
    }
    public function shopDetailsAction($id){
        $product=$this->getDoctrine()->getRepository(product::class)->find($id);
        return $this->render('@Shop/Default/shop-details.html.twig',['product'=>$product]);
    }
    public function afficherShopAction(){
        $em=$this->getDoctrine();
        $rep=$em->getRepository(product::class);
        $products=$rep->findAll();

        return $this->render('@Shop/Default/index.html.twig',['products'=>$products]);
    }

}
