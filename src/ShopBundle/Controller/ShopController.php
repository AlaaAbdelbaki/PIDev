<?php

namespace ShopBundle\Controller;

use AppBundle\Entity\product;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
    public function afficherShopAction( Request $request){
        $em=$this->getDoctrine();
        $rep=$em->getRepository(product::class);
        $products=$rep->findAll();
        $paginator = $this->get(PaginatorInterface::class);
        $pagination = $paginator->paginate($products, $request->query->getInt('page', 1), 4);

        return $this->render('@Shop/Default/index.html.twig',['products'=>$pagination]);
    }

}
