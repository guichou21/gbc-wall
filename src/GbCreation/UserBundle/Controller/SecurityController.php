<?php
namespace GbCreation\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
#use FOS\UserBundle\Controller\SecurityController as BaseController;

/**
 * Security controller.
 */
class SecurityController extends Controller
{

    public function loginAction()
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'admin
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
          return $this->redirect($this->generateUrl('gb_creation_admin__index'));
         }
 
        $request = $this->getRequest();
        $session = $request->getSession();
     
        // On vérifie s'il y a des erreurs d'une précédente soumission du formulaire
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
          $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
          $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
          $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
     
        return $this->render('GbCreationUserBundle:Security:login.html.twig', array(
          // Valeur du précédent nom d'utilisateur entré par l'internaute
          'last_username' => $session->get(SecurityContext::LAST_USERNAME),
          'error'         => $error,
        ));
  }

   public function registerFirstTimeAction()
    {
        // Si le visiteur est déjà identifié, on le redirige vers l'admin
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
          return $this->redirect($this->generateUrl('gb_creation_admin__index'));
         }
 
        $request = $this->getRequest();
        $session = $request->getSession();
     
        // On vérifie s'il y a des erreurs d'une précédente soumission du formulaire
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
          $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
          $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
          $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
     
        return $this->render('GbCreationUserBundle:Security:login.html.twig', array(
          // Valeur du précédent nom d'utilisateur entré par l'internaute
          'last_username' => $session->get(SecurityContext::LAST_USERNAME),
          'error'         => $error,
        ));
  }




  /*
http://www.siteduzero.com/informatique/tutoriels/developpez-votre-site-web-avec-le-framework-symfony2/utiliser-fosuserbundle
// Dans un contrôleur :
 
// Pour récupérer le service UserManager du bundle
$userManager = $this->get('fos_user.user_manager');
 
// Pour charger un utilisateur
$user = $userManager->findUserBy(array('username' => 'winzou'));
 
// Pour modifier un utilisateur
$user->setEmail('cetemail@nexiste.pas');
$userManager->updateUser($user); // Pas besoin de faire un flush avec l'EntityManager, cette méthode le fait toute seule !
 
// Pour supprimer un utilisateur
$userManager->deleteUser($user);
 
// Pour récupérer la liste de tous les utilisateurs
$users = $userManager->findUsers();


ou $this->getDoctrine()->getManager()->getRepository('SdzUserBundle:User'). 

  */

   /**
* On modifie la façon dont est choisie la vue lors du rendu du formulaire de connexion
*
* Je sais que c'est cette méthode qu'il faut hériter car j'ai été voir le contrôleur d'origine du bundle :
* https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Controller/SecurityController.php
*//*
  protected function renderLogin(array $data)
  {
    // Sur la page du formulaire de connexion, on utilise la vue classique "login"
    // Cette vue hérite du layout et ne peut donc être utilisée qu'individuellement
    if ($this->container->get('request')->attributes->get('_route') == 'fos_user_security_login') {
      $view = 'login';
    } else {
      // Mais sinon, il s'agit du formulaire de connexion intégré au menu, on utilise la vue "login_content"
      // car il ne faut pas hériter du layout !
      $view = 'login_content';
    }

    $template = sprintf('FOSUserBundle:Security:%s.html.%s', $view, $this->container->getParameter('fos_user.template.engine'));

    return $this->container->get('templating')->renderResponse($template, $data);
  }
*/


}