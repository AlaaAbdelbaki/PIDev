<?php

namespace ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use AppBundle\Entity\product;
use AppBundle\Entity\orders;
use AppBundle\Entity\order_line;

class ShopApiController extends Controller
{
    public function showAllProductsMobileAction(){
        $em=$this->getDoctrine();
        $rep=$em->getRepository(product::class);
        $products=$rep->findAll();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });


        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($products);
        return new JsonResponse($formatted);

    }

    public function addproductMobileAction(){
        $em = $this->getDoctrine()->getManager();
        $product = new product();
        $product->setProductName("test name");
        $product->setImg("test img");
        $product->setStock(6);
        $product->setPrice(666);
        $em->persist($product);
        $em->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($product);
        return new JsonResponse($formatted);
    }

    public function addOrderAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $order = new orders();

        $order -> setAddress($request->get('address'));
        $order -> setTotal($request->get('total'));
        $order -> setOrderDate(new \DateTime('now'));

        $em -> persist($order);
        $em -> flush();



        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($order);
        return new JsonResponse($formatted);

    }

    public function addOrderLineAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $product_id = $request->get('productid');
        $quantity = $request->get('quantity');

        $conn = $this->getDoctrine()->getConnection();
        $stmt = $conn->prepare("select * from orders order by id desc ");
        $stmt->execute();
        $order_id = $stmt->fetch()['id'];


        $order_line = new order_line();

        $product_objet = $this->getDoctrine()->getRepository(product::class)->findById($product_id);
        $order_object = $this->getDoctrine()->getRepository(orders::class)->findById($order_id);

        $idp=$product_objet[0];
        $ido=$order_object[0];


        $order_line->setQuantity($quantity);
        $order_line->setProduct($idp);
        $order_line->setOrders($ido);


        $em -> persist($order_line);
        $em -> flush();

        $conn = $this->getDoctrine()->getConnection();
        $stmt = $conn->prepare("update product set stock=(stock-$quantity) where id=$product_id");
        $stmt->execute();


        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($order_line);
        return new JsonResponse($formatted);
    }

    public function countquantityAction(){
        $em=$this->getDoctrine();
        $rep=$em->getRepository(product::class);
        $products=$rep->countquantity();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);

        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });


        $serializer = new Serializer([$normalizer]);
        $formatted = $serializer->normalize($products);
        return new JsonResponse($formatted);
    }

    public function showProductNameOneAction($id){
        $em=$this->getDoctrine();
        $rep=$em->getRepository(product::class);
        $products=$rep->findProductNameForOneProduct($id);


        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($products);
        return new JsonResponse($formatted);
    }

}
