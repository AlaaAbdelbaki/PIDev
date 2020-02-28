<?php

namespace AppBundle\Controller;

use AppBundle\Entity\video;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {  if ($this->getUser()){
        $posts=$this->getDoctrine()->getRepository(video::class)->findBy(array(),array('publishDate' => 'DESC'));
        $paginator = $this->get(PaginatorInterface::class);
        $pagination = $paginator->paginate($posts, $request->query->getInt('page', 1), 5);
        return $this->render('default/feed.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,'posts'=>$pagination
        ]);
    }
        // replace this example code with whatever you need
        return $this->render('default/home.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/admin", name="admin")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function adminAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('admin_base.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("post/{id}", name="post")
     * @param $id
     * @return Response
     */
    public function postAction($id)
    {
        $post=$this->getDoctrine()->getRepository(video::class)->find($id);
        return ($this->render('default/post.html.twig',['post'=>$post])
        );
    }
    /**
     * @Route("feed/ranking/", name="ranks_feed")

     * @return Response
     */
    public function updateRanksAction()
    {
        $ranks = $this->getDoctrine()->getRepository(video::class)->findRanks();

        $res = new ArrayCollection();
        foreach ($ranks as $r) {
            $vid = $this->getDoctrine()->getRepository(video::class)->findById($r['video_id']);
        dump($vid);
            $res->add($vid);

        }
dump($res);
        return ($this->render('default/ranks.html.twig', array('res' => $res))
        );
    }
}
