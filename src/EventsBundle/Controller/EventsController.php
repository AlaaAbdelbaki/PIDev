<?php

namespace EventsBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\event;
use AppBundle\Entity\ticket;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ob\HighchartsBundle\Highcharts\Highchart;
use WBW\Bundle\HighchartsBundle\API\HighchartsChart;


class EventsController extends Controller
{
    public function addAction(Request $request)
    {
     $event=new event();

     $form=$this->createFormBuilder($event)
         ->add('title',TextType::class)
         ->add('startDate',DateType::class)
         ->add('endDate',DateType::class)
         ->add('img', FileType::class, array('data_class'=>null, 'required'=>false))
         ->add('location',TextType::class)
         ->add('nb_places',NumberType::class)
         ->add('description',TextType::class)
         ->add('type',ChoiceType::class,["choices"=>["audition"=>"audition","casting"=>"casting","offre emploi"=>"offre emploi","concert"=>"concert"]])
         ->add('Submit', SubmitType::class)
         ->getForm();
     $form->handleRequest($request);

     if($form->isSubmitted() && $form->isValid())
     {
         $file = $event->getImg();
         $fileName = md5(uniqid()).'.'.$file->guessExtension();
         $file->move($this->getParameter('photos_directory'), $fileName);
         $event->setImg($fileName);
         $em = $this->getDoctrine()->getManager();
         $em->persist($event);
         $em->flush();

         return $this->redirect($this->generateUrl('show_events_admin'));
     }
     return($this->render("@Events/event/event_add.html.twig",['f'=>$form->createView()]));


    }


    public function afficheAction()
    {
        $tab=$this->getDoctrine()->getRepository(event::class)->findAll();
//        var_dump($tab);
        return $this->render('@Events/event/affiche.html.twig',array('t'=>$tab));
    }


    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $event =$em->getRepository(event::class)->find($id);

        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('show_events_admin');
    }

    public function modifierAction(Request $request,$id){
        $event=$this->getDoctrine()->getRepository(event::class)->find($id);


        $form=$this->createFormBuilder($event)
            ->add('title',TextType::class)
            ->add('startDate',DateType::class)
            ->add('endDate',DateType::class)
            ->add('img', FileType::class, array('data_class'=>null, 'required'=>false))
            ->add('location',TextType::class)
            ->add('nb_places',NumberType::class)
            ->add('description',TextType::class)
            ->add('type',ChoiceType::class,["choices"=>["audition"=>"audition","casting"=>"casting","offre emploi"=>"offre emploi","concert"=>"concert"]])
            ->add('Submit', SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $file = $event->getImg();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $fileName);
            $event->setImg($fileName);
            $conn = $this->getDoctrine()->getManager();
            $conn->persist($event);
            $conn->flush();

            return $this->redirect($this->generateUrl('show_events_admin'));
        }

        return $this->render('@Events/event/modifier.html.twig',['event'=>$event,'f'=>$form->createView()]);

    }

    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $event =  $em->getRepository('AppBundle:event')->findEntitiesByString($requestString);
        if(!$event) {
            $result['event']['error'] = "event Not found :( ";
        } else {
            $result['event'] = $this->getRealEntities($event);
        }
        return new Response(json_encode($result));
    }
    public function getRealEntities($event){
        foreach ($event as $event){
            $realEntities[$event->getId()] = [$event->getImg(),$event->getTitle()];

        }
        return $realEntities;
    }
    public function filterAction(Request $request)
    {
        $requestString = $request->get('Type');
        dump($request->get('type'));
        $events =  $this->getDoctrine()->getRepository('AppBundle:event')->findByType(['Type'=>$requestString]);
        dump($events);
        return $this->render("@Events/default/affiche.html.twig",["t"=>$events]);


    }
    public function triEndDAction()
    {
        $tab=$this->getDoctrine()->getRepository(event::class)->orderEndD();
        return $this->render('@Events/default/affichetri.html.twig',array('t'=>$tab));
    }

    public function statsUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $total = $em->getRepository("AppBundle:ticket")->findAll();
        $event = $em->getRepository("AppBundle:event")->findAll();
        $nbTot = count($total);
        #var_dump($nbTot);



        $eve = $em->getRepository("AppBundle:ticket")->findByoffre();
        $nb_offre = (($eve / $nbTot) * 100);


        $cas = $em->getRepository("AppBundle:ticket")->findBycasting();
        $nb_casting =(($cas / $nbTot) * 100);

        $concert = $em->getRepository("AppBundle:ticket")->findByconcert();
        $nb_concert = (($concert / $nbTot) * 100);

        $audition = $em->getRepository("AppBundle:ticket")->findByaudition();
        $nb_audition = (($audition / $nbTot) * 100);


        // Prepare the data.
        // $data = [["name" => "casting","casting" => 20],["name" => "audition","audition" => 25],["name" => "offre","offre" => 25],[ "name" => "concert","concert" => 30]];
        // $data = [["name" => "Female", "y" => 25 ], ["name" => "Male", "y" => 25], ["name" => "Unknown", "y" => 50]];
        $data = [["name" => "casting" , "y" => $nb_casting],["name" => "audition","y" => $nb_audition],["name" => "offre","y" => $nb_offre],["name" => "concert","y" => $nb_concert]];
        // var_dump($data);


        // Initialize the series.
        $series = [["colorByPoint" => true, "data" => $data, "name" => "event type distribution"]];

        // Initialize the chart.
        $chart = new HighchartsChart;
        $chart->newChart()->setType("pie");
        $chart->newPlotOptions()->newPie()
            ->setAllowPointSelect(true)
            ->setCursor("pointer")
            ->setShowInLegend(true)
            ->newDataLabels()->setEnabled(true);
        $chart->setSeries($series);
        $chart->newTitle()->setText("event type distribution");
        $chart->newTooltip()->setPointFormat("{series.name}: <b>{point.percentage:.1f}%</b>");

        return $this->render('@Events/event/test.html.twig', [
            'chart' => $chart
        ]);



    }


}
