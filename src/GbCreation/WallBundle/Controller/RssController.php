<?php
namespace GbCreation\WallBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Rss controller.
 */
class RssController extends Controller
{
    public function feedAction()
    {
         return $this->itemAction();
    }

    public function itemAction()
    {
        $RSS = $this->container->getParameter('RSS');

        $em = $this->getDoctrine()
                   ->getManager();

        $items = $em->getRepository('GbCreationWallBundle:Item')->getLastItems($RSS['NB_MAX_ITEM']);
    
         return $this->render('GbCreationWallBundle:Rss:item.html.twig',array(
            'items' => $items,
        ));
    }

    public function commentAction()
    {
        $RSS = $this->container->getParameter('RSS');

        $em = $this->getDoctrine()
                   ->getManager();

        $comments = $em->getRepository('GbCreationWallBundle:Comment')->getLastComments($RSS['NB_MAX_COMMENT']);
    

         return $this->render('GbCreationWallBundle:Rss:comment.html.twig',array(
            'comments' => $comments,
        ));
    }

}