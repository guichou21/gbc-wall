<?php

namespace GbCreation\WallBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GbCreation\WallBundle\Entity\Comment;
use GbCreation\WallBundle\Form\CommentType;

/**
 * Comment controller.
 */
class CommentController extends Controller
{
    public function newAction($item_id)
    {
        $item = $this->getIdItem($item_id);

        $comment = new Comment();
        $comment->setIdItem($item);
        $form   = $this->createForm(new CommentType(), $comment);

        return $this->render('GbCreationWallBundle:Comment:form.html.twig', array(
            'comment' => $comment,
            'form'   => $form->createView()
        ));
    }

    public function createAction($item_id)
    {
        $item = $this->getIdItem($item_id);

        $comment  = new Comment();
        $comment->setIdItem($item);
        $request = $this->getRequest();
        $form    = $this->createForm(new CommentType(), $comment);
        //$form->bindRequest($request);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();


            // On récupère le service antispam
            $antispam = $this->container->get('gb_creation_wall.antispam');
         
            // On verifie le contenu du text
            if ($antispam->isSpam($comment->getComment())) {
              throw new \Exception('Votre message a été détecté comme spam !');
            }

            $em->persist($comment);


            $itemReference = $em->getRepository('GbCreationWallBundle:Item')->find($item_id);
            $itemReference->setNbLike($itemReference->getNbLike()+1);
            //pas besoin de persist car récupéré par Doctrine2
            //$em->persist($itemReference);

            $em->flush();

            return $this->redirect($this->generateUrl('gb_creation_wall__show', array(
                'id' => $comment->getIdItem()->getId())) .
                '#comment-' . $comment->getId()
            );
        }

        return $this->render('GbCreationWallBundle:Comment:create.html.twig', array(
            'comment' => $comment,
            'form'    => $form->createView()
        ));
    }

    protected function getIdItem($item_id)
    {
        $repository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('GbCreationWallBundle:Item');

        $item = $repository->find($item_id);

        if (!$item) {
            throw $this->createNotFoundException('Unable to find Blog post.');
        }

        return $item;
    }

}