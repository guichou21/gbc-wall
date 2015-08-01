<?php

namespace GbCreation\WallBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use GbCreation\WallBundle\Entity\Item;
use GbCreation\WallBundle\Form\ItemType;
use GbCreation\WallBundle\Form\ItemVideoType;
use GbCreation\WallBundle\Form\ItemEditedType;
use GbCreation\WallBundle\Form\CommentEditedType;


/*

En attendant que  JMSSecurityExtraBundle  soir OK avec symfony2.3.1 on fait des if else
=> permet Annotation sur chaque fonction :  @_S_e_c_u_r_e_(_r_o_l_e_s_=_"_R_O_L_E___A_U_T_E_U_R_"_)
Alternative : https://github.com/LeaseWeb/LswSecureControllerBundle
http://www.leaseweblabs.com/2013/06/secure-annotation-for-symfony-2-3/
*/


/**
 * Admin controller.
 */
class AdminController extends Controller
{
    public function indexAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          throw new AccessDeniedHttpException('Accès limité aux Admins');
        }
        return $this->render('GbCreationWallBundle:Admin:index.html.twig');
    }

    public function statsAction()
    {

        $em = $this->getDoctrine()
                   ->getManager();

        $nbPictureItems = $em->getRepository('GbCreationWallBundle:Item')->countAllItemsByType('Picture');
        $nbVideoItems = $em->getRepository('GbCreationWallBundle:Item')->countAllItemsByType('Video');
        $nbComments = $em->getRepository('GbCreationWallBundle:Comment')->countAllComments();

        return $this->render('GbCreationWallBundle:Admin:stats.html.twig', array(
                'nbPictureItems' => $nbPictureItems,
                'nbVideoItems' => $nbVideoItems,
                'nbComments' => $nbComments,
            ));
    }

/* notmalement new ???*/ 
	/**
     * Displays a form to create a new Item entity.
     *
     */
    public function addAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          throw new AccessDeniedHttpException('Accès limité aux Admins');
        }

        $entity = new Item();
        $form   = $this->createForm(new ItemType(), $entity);
 
        $video = new Item();
        $video->setType("Video");
        $formVideo   = $this->createForm(new ItemVideoType(), $video);

        return $this->render('GbCreationWallBundle:Admin:add.html.twig', array(
            'form'   => $form->createView(),
            'formVideo' => $formVideo->createView(),
        ));
    }

    public function allItemsAction()
    {
         if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          throw new AccessDeniedHttpException('Accès limité aux Admins');
        }

        $em = $this->getDoctrine()->getManager();
        
        $items = $em->getRepository('GbCreationWallBundle:Item')
                ->getAllItemsByType('Picture');
  
        
        return $this->render('GbCreationWallBundle:Admin:allItems.show.html.twig', array(
            'items' => $items,
        ));
    }

    public function itemsAction($page)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          throw new AccessDeniedHttpException('Accès limité aux Admins');
        }

        $em = $this->getDoctrine()->getManager();
        $params = $this->container->getParameter('Pagination');


        $nbPerPage = $params['PAGINATION_NB_ITEM_PER_PAGE'];
        $nbTotal    = $em->getRepository('GbCreationWallBundle:Item')
                ->countAllItemsByType('Picture');

        $last_page         = ceil($nbTotal / $nbPerPage);
        $previous_page     = $page > 1 ? $page - 1 : 1;
        $next_page         = $page < $last_page ? $page + 1 : $last_page;

        $items = $em->getRepository('GbCreationWallBundle:Item')
                ->getAllItemsPaginatedByType($page,$nbPerPage,'Picture');


        //var_dump($items);die();
        
        return $this->render('GbCreationWallBundle:Admin:items.show.html.twig', array(
            'items' => $items,
            'last_page' => $last_page,
            'current_page' => $page,
            'previous_page' => $previous_page,
            'next_page' => $next_page,
            'total_item' => $nbTotal,
        ));
    }

    public function allVideoItemsAction()
    {
         if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          throw new AccessDeniedHttpException('Accès limité aux Admins');
        }

        $em = $this->getDoctrine()->getManager();
        
        $items = $em->getRepository('GbCreationWallBundle:Item')
                ->getAllItemsByType('Video');
        
        
        return $this->render('GbCreationWallBundle:Admin:allVideoItems.show.html.twig', array(
            'items' => $items,
        ));
    }

     public function videoItemsAction($page)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          throw new AccessDeniedHttpException('Accès limité aux Admins');
        }

        $em = $this->getDoctrine()->getManager();
        $params = $this->container->getParameter('Pagination');


        $nbPerPage = $params['PAGINATION_NB_ITEM_PER_PAGE'];
        $nbTotal = $em->getRepository('GbCreationWallBundle:Item')
                ->countAllItemsByType('Video');

        $last_page         = ceil($nbTotal / $nbPerPage);
        $previous_page     = $page > 1 ? $page - 1 : 1;
        $next_page         = $page < $last_page ? $page + 1 : $last_page;

        $items = $em->getRepository('GbCreationWallBundle:Item')
                ->getAllItemsPaginatedByType($page,$nbPerPage,'Video');


        //var_dump($items);die();
        
        return $this->render('GbCreationWallBundle:Admin:videoItems.show.html.twig', array(
            'items' => $items,
            'last_page' => $last_page,
            'current_page' => $page,
            'previous_page' => $previous_page,
            'next_page' => $next_page,
            'total_item' => $nbTotal,
        ));
    }


    /**
     * Creates a new Item entity.
     *
     */
    public function createItemAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          throw new AccessDeniedHttpException('Accès limité aux Admins');
        }

        $params = $this->container->getParameter('Upload');

        $entity  = new Item();
        $request = $this->getRequest();

        $form    = $this->createForm(new ItemType(), $entity);

        $video = new Item();
        $video->setType("Video");
        $formVideo   = $this->createForm(new ItemVideoType(), $video);

        //$form->bindRequest($request);
        $form->handleRequest($request);
 
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /* ne plus appeler le move vu que callbacks*/
            //$entity->upload();

            $extensions = $params['extensions'];
            $extension = strtolower(strrchr($entity->fileToUpload->getClientOriginalName(), '.'));

            if(!in_array($extension, $extensions)){  
                $this->get('session')->getFlashBag()->add('error', 'Fichier non autorisé');
                return $this->render('GbCreationWallBundle:Admin:add.html.twig', array(
                    'form'   => $form->createView(),
                    'formVideo'   => $formVideo->createView(),
                    ));
            }


            $em->persist($entity);
            $em->flush();
 
            $this->get('session')->getFlashBag()->add('notice', 'Ajout a été effectué');
            return $this->redirect($this->generateUrl('gb_creation_admin__add'));
            //return $this->redirect($this->generateUrl('gb_creation_admin__index', array('id' => $entity->getId())));     
        }

 
        return $this->render('GbCreationWallBundle:Admin:add.html.twig', array(
            'form'   => $form->createView(),
            'formVideo' => $form->createView(),
        ));
    }
     
     /**
     * Creates a new Item entity.
     *
     */
    public function createVideoItemAction()
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          throw new AccessDeniedHttpException('Accès limité aux Admins');
        }

        $params = $this->container->getParameter('Upload');

        $logger = $this->get('logger');

        //cree le form pour nettoyer au cas ou ajout phot derière
        $picture  = new Item();
        $form    = $this->createForm(new ItemType(), $picture);


        $entity  = new Item();
        $request = $this->getRequest();

        $formVideo    = $this->createForm(new ItemVideoType(), $entity);

        $formVideo->handleRequest($request);
 
        if ($formVideo->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $videoWebSite = $params['videoWebSite'];
            $myurl = strtolower($entity->getFile());
            $logger->info('[createVideoItemAction] tenative creation avec url : ['.$myurl.']');

            $authorized = 'false';
            foreach ($videoWebSite as $authorizedUrl) {
                if(stripos($myurl,$authorizedUrl) !== false){
                    $logger->info('[createVideoItemAction]  url autorisée ');
                    $authorized ='true';
                }
            }
            if($authorized == 'false'){ 
                $logger->info('[createVideoItemAction]  url NON autorisée '); 
                $this->get('session')->getFlashBag()->add('error', 'Url non autorisée');
                return $this->render('GbCreationWallBundle:Admin:add.html.twig', array(
                    'formVideo'   => $formVideo->createView(),
                    'form' => $form->createView(),
                    ));
            }

            $entity->setType("Video");
            $em->persist($entity);
            $em->flush();
 
            $this->get('session')->getFlashBag()->add('notice', 'Ajout a été effectué');
            return $this->redirect($this->generateUrl('gb_creation_admin__add'));
            //return $this->redirect($this->generateUrl('gb_creation_admin__index', array('id' => $entity->getId())));     
        }

 
        return $this->render('GbCreationWallBundle:Admin:add.html.twig', array(
            'formVideo'   => $formVideo->createView(),
            'form' => $form->createView(),
        ));
    }

    private function createDeleteForm($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          throw new AccessDeniedHttpException('Accès limité aux Admins');
        }

        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

     public function editItemAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          throw new AccessDeniedHttpException('Accès limité aux Admins');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $entity = $entityManager->getRepository("GbCreationWallBundle:Item")->find($id);
          
       $form = $this->createForm(new ItemEditedType(), $entity);
     
     
        /* On récupère les données du formulaire si il a déjà été passé */
        $request = $this->get('request');
     
        /* On ne traite que les données passées en méthode POST */
        if ($request->getMethod() == 'POST') {
            /* On applique les données récupérées au formulaire */
            $form->handleRequest($request);
     
            /* Si le formulaire est valide, on valide et on redirige vers l'accueil' */
            if ($form->isValid()) {               
                $entityManager->persist($entity);
                $entityManager->flush();
     
                $this->get('session')->getFlashBag()->add('notice', 'modification a été prise en compte');
                if($entity->getType() == "Video"){
                    return $this->redirect($this->generateUrl('gb_creation_admin_items_video_show'));
                }
                else{
                    return $this->redirect($this->generateUrl('gb_creation_admin_items_show'));   
                }
            }
        }
 
        /* Si aucun formulaire valide n'est récupéré, on affiche le formulaire */
        return $this->render('GbCreationWallBundle:Admin:edit.html.twig', array(
            "form" => $form->createView(),
            "item_id" => $entity->getId(),
            "item_name" => $entity->getFile(),
            "itemType" => $entity->getType(),
        ));
    }

    public function deleteItemAction($id)
        {
            if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
              throw new AccessDeniedHttpException('Accès limité aux Admins');
            }

                $entityManager = $this->getDoctrine()->getManager();
                $entity = $entityManager->getRepository("GbCreationWallBundle:Item")->find($id);
                $type = $entity->getType();
                $entityManager->remove($entity);   
                $entityManager->flush();
         
                // On définit un message flash
                $this->get('session')->getFlashBag()->add('notice', 'item bien supprimé');
         
                // Puis on redirige vers l'accueil
                 if($type == "Video"){
                    return $this->redirect($this->generateUrl('gb_creation_admin_items_video_show'));
                }
                else{
                    return $this->redirect($this->generateUrl('gb_creation_admin_items_show'));  
                }

                
        }



        public function allCommentsAction()
        {
             if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
                throw new AccessDeniedHttpException('Accès limité aux Admins');
             }

            $em = $this->getDoctrine()->getManager();

            $comments = $em->getRepository('GbCreationWallBundle:Comment')
                ->getAllCommentsForBlogWithItem();
                   
            return $this->render('GbCreationWallBundle:Admin:allComments.show.html.twig', array(
                'comments' => $comments
            ));
        }

         public function commentsAction($page)
        {
             if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
                throw new AccessDeniedHttpException('Accès limité aux Admins');
             }

            $em = $this->getDoctrine()->getManager();
            $params = $this->container->getParameter('Pagination');


            $nbPerPage = $params['PAGINATION_NB_ITEM_PER_PAGE'];
            $nbTotal    = $em->getRepository('GbCreationWallBundle:Comment')
                    ->countAllComments();

            $last_page         = ceil($nbTotal / $nbPerPage);
            $previous_page     = $page > 1 ? $page - 1 : 1;
            $next_page         = $page < $last_page ? $page + 1 : $last_page;

            $comments = $em->getRepository('GbCreationWallBundle:Comment')
                    ->getAllCommentsForBlogWithItemPaginated($page,$nbPerPage);


        return $this->render('GbCreationWallBundle:Admin:comments.show.html.twig', array(
                'comments' => $comments,
                 'last_page' => $last_page,
                'current_page' => $page,
                'previous_page' => $previous_page,
                'next_page' => $next_page,
                'total_item' => $nbTotal,
            ));
        }
 
    public function editCommentAction($id)
        {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          throw new AccessDeniedHttpException('Accès limité aux Admins');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $entity = $entityManager->getRepository("GbCreationWallBundle:Comment")->find($id);
          
       $form = $this->createForm(new CommentEditedType(), $entity);
     
     
        /* On récupère les données du formulaire si il a déjà été passé */
        $request = $this->get('request');
     
        /* On ne traite que les données passées en méthode POST */
        if ($request->getMethod() == 'POST') {
            /* On applique les données récupérées au formulaire */
            $form->handleRequest($request);
     
            /* Si le formulaire est valide, on valide et on redirige vers l'accueil' */
            if ($form->isValid()) {               
                $entityManager->persist($entity);
                $entityManager->flush();

                $this->get('session')->getFlashBag()->add('notice', 'modification a été prise en compte');
                return $this->redirect($this->generateUrl('gb_creation_admin_comments_show'));
            }
        }
 
        /* Si aucun formulaire valide n'est récupéré, on affiche le formulaire */
        return $this->render('GbCreationWallBundle:Admin:comment.edit.html.twig', array(
            "form" => $form->createView(),
            "comment_id" => $entity->getId(),
        ));
    }

    public function deleteCommentAction($id)
        {
            if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
                throw new AccessDeniedHttpException('Accès limité aux Admins');
             }

            $entityManager = $this->getDoctrine()->getManager();
            $entity = $entityManager->getRepository("GbCreationWallBundle:Comment")->find($id);

            $itemReference = $entityManager->getRepository('GbCreationWallBundle:Item')->find($entity->getIdItem()->getId());
            $itemReference->setNbLike($itemReference->getNbLike()-1);

            $entityManager->remove($entity);   
            $entityManager->persist($itemReference);

            $entityManager->flush();
     
            // On définit un message flash
            $this->get('session')->getFlashBag()->add('notice', 'Commentaire bien supprimé');
     
            // Puis on redirige vers l'accueil
            return $this->redirect($this->generateUrl('gb_creation_admin_comments_show'));         
        }


     public function ___deleteCommentAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          throw new AccessDeniedHttpException('Accès limité aux Admins');
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'item contre cette faille
        $form = $this->createFormBuilder()->getForm();
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
          $form->handleRequest($request);
     
          if ($form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entity = $entityManager->getRepository("GbCreationWallBundle:Comment")->find($id);;
            $entityManager->remove($entity);   
            $entityManager->flush();
     
            // On définit un message flash
            $this->get('session')->getFlashBag()->add('notice', 'Commentaire bien supprimé');
     
            // Puis on redirige vers l'accueil
            return $this->redirect($this->generateUrl('gb_creation_admin_comments_show'));
          }
        }
         
            // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
            return $this->render('GbCreationWallBundle:Admin:comment.delete.html.twig', array(
              'comment_id' => $id,
              'form'    => $form->createView()
            ));
    }


    public function getImagesPathForEdit()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__ . '/../../../../web/images/wall/';
    }

    public function editItemRotateAction($rotate,$id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
          throw new AccessDeniedHttpException('Accès limité aux Admins');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entity = $entityManager->getRepository("GbCreationWallBundle:Item")->find($id);
        
        //recuperation des noms des images
        $imageName = $this->getImagesPathForEdit().$entity->getFile();
        $thumbImageName = $this->getImagesPathForEdit().'m_'.$entity->getFile();

        //Creation des images
        $imageSource = imagecreatefromjpeg($imageName);
        $thumbSource = imagecreatefromjpeg($thumbImageName);

        //Creation des images avec la rotation
        if($rotate == 'left'){
            $imageR = imagerotate($imageSource,90,0);
            $thumbR = imagerotate($thumbSource,90,0);
        }
        else if ($rotate == 'right'){
            $imageR = imagerotate($imageSource,-90,0);
            $thumbR = imagerotate($thumbSource,-90,0);
        }


        //sauvegarde
        imagejpeg($imageR,$imageName);
        imagejpeg($thumbR,$thumbImageName);

        //Mise a jour images en base (ratio)
        $temp = $entity->getRatio();
        $entity->setRatio($entity->getReverseRatio());
        $entity->setReverseRatio($temp);

        $entityManager->persist($entity);
        $entityManager->flush();



        //liberation espace memoire
        imagedestroy($imageSource);
        imagedestroy($thumbSource);

        imagedestroy($imageR);
        imagedestroy($thumbR);       
     
 
        // Puis on redirige sur la page   
        return $this->redirect($this->generateUrl('gb_creation_admin_item_edit', array('id' => $id)));    

      
    }

}