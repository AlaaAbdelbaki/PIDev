<?php

namespace ShopBundle\Controller;

use AppBundle\Entity\product;
use AppBundle\Entity\orders;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Repository\productRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CartController extends Controller
{
    public function ajouterCartAction($id,SessionInterface $session){
        $panier = $session->get('panier',[]);
        if(!empty($panier[$id]))
        {
            $panier[$id]++;
        }else{
            $panier[$id]=1;
        }
        $session->set('panier',$panier);
        return $this->redirectToRoute('panier_afficher');
    }

    public function afficherCartAction(SessionInterface $session ){
        $panier = $session->get('panier',[]);

        $panierWithData= [];
        foreach ($panier as $id => $quantity){

            $panierWithData[]=[
                'product' => $this->getDoctrine()->getManager()->getRepository('AppBundle:product')->findById($id),
                'quantity' => $quantity
            ];
        }

        return $this->render('@Shop/Default/panier_index.html.twig',array(

            'items' => $panierWithData

        ));
    }

    public function supprimerCartAction($id,SessionInterface $session){
        $panier = $session->get('panier',[]);
        if(!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $session->set('panier',$panier);
        return $this->redirectToRoute('panier_afficher');
    }

    public function savePdfAction(Request $request,SessionInterface $session){
        $panier = $session->get('panier',[]);

        $panierWithData= [];
        foreach ($panier as $id => $quantity){

            $panierWithData[]=[
                'product' => $this->getDoctrine()->getManager()->getRepository('AppBundle:product')->findById($id),
                'quantity' => $quantity
            ];
        }
        $snappy = $this->get('knp_snappy.pdf');

        $html = $this->renderView('ShopBundle:Default:pdf.html.twig', array(
            'items' => $panierWithData
        ));

        $filename = 'myFirstSnappyPDF';

        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }
    public function showPdfAction(SessionInterface $session ){
        $panier = $session->get('panier',[]);
        $em= $this->getDoctrine()->getManager();
        $products= $em->getRepository('AppBundle:product')->findByArray(array_keys($session->get('panier')));
        $total=0;
        foreach ($products as $product){
            $totalP= $product->getPrice() + $total;

        }

        $panierWithData= [];
        foreach ($panier as $id => $quantity){

            $panierWithData[]=[
                'product' => $this->getDoctrine()->getManager()->getRepository('AppBundle:product')->findById($id),
                'quantity' => $quantity
            ];
        }




        return $this->render('@Shop/Default/pdf.html.twig',array(

            'items' => $panierWithData

        ));
    }

    public function userAddCartAction(Request $request , SessionInterface $session){
        if ($request->getMethod() == Request::METHOD_POST){
            $orders = new orders();
            $total = $request->request->get('total');
            $address = $request->request->get('address');

            $orders->setOrderDate(new \DateTime('now'));
            $orders->setAddress($address);
            $orders->setTotal($total);


            $conn = $this->getDoctrine()->getManager();
            $conn->persist($orders);
            $conn->flush();
            $session->clear();

        }
        return $this->redirect($this->generateUrl('shop_homepage'));
    }
}
