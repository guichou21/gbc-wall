<?php
namespace GbCreation\HomeBundle\Controller;

use GbCreation\HomeBundle\Entity\Contact;
use GbCreation\HomeBundle\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
     public function contactAction()
    {
        $logger = $this->get('logger');
        $logger->info('[contactAction] Formulaire de contact');
        $contact = new Contact();
        $form   = $this->createForm(new ContactType(), $contact);

         /* On récupère les données du formulaire si il a déjà été passé */
        $request = $this->get('request');

        /* On ne traite que les données passées en méthode POST */
        if ($request->getMethod() == 'POST') {
            /* On applique les données récupérées au formulaire */
            $form->handleRequest($request);
            //var_dump($form);
            //die();
            /* Si le formulaire est valide, on valide et on redirige vers l'accueil' */
            if ($form->isValid()) {               

                $logger->info('[contactAction] form is valid');
                // Bind value with form
                
                $data = $form->getData();
                $logger->info('[contactAction] envoi depuis mail {' + $data->getEmail()+'}');
                
                $this->sendEmail($data);

                $logger->info('[contactAction] envoie du mail effectue');
                $this->get('session')->getFlashBag()->add('notice', 'Votre Email a été envoyé. merci de nous avoir contacté'); 
                $form   = $this->createForm(new ContactType(), new Contact());
                //$this->get('session')->setFlash('notice', 'Votre Email a été envoyé. merci de nous avoir contacté');
            }
        }
        
        return $this->render('GbCreationHomeBundle:Contact:contact.html.twig',
        array(
            'form' => $form->createView(),
        ));

    }
       

        /**
         * Send mail 
         *
         * @param array $data
         *
         */
        protected function sendEmail($data)
        {

            $logger = $this->get('logger');
            $params = $this->container->getParameter('Contact');
            $EMAIL_WEBSITE_CONTACT = $params['EMAIL_WEBSITE_CONTACT'];

            $logger->info('[contactAction] sendEmail');

            $emailContent = $data->getContent();

            // On récupère le service antispam
            $antispam = $this->container->get('gb_creation_wall.antispam');

             // On verifie le contenu du text
            if ($antispam->isSpam($emailContent)) {
              $logger->info('[contactAction] Contenu detecte en tant que SPAM');
              throw new \Exception('Votre message a été détecté comme spam !');
            }

             $message = \Swift_Message::newInstance()
                    ->setContentType('text/html')
                    ->setSubject($data->getSubject())
                    ->setFrom($data->getEmail())
                    ->setTo($EMAIL_WEBSITE_CONTACT)
                    ->setBody($emailContent)
                ;
               $this->get('mailer')->send($message);
        }


/* si marche pas - regarder avec le CDATA ?
                    $message = \Swift_Message::newInstance()
                        ->setContentType('text/html')
                        ->setSubject($data['subject'])
                        ->setFrom($data['email'])
                        ->setTo('xxxxxx@gmail.com<script type="text/javascript">
                        /* <![CDATA[ */
/*                        (function(){try{var s,a,i,j,r,c,l,b=document.getElementsByTagName("script");l=b[b.length-1].previousSibling;a=l.getAttribute('data-cfemail');if(a){s='';r=parseInt(a.substr(0,2),16);for(j=2;a.length-j;j+=2){c=parseInt(a.substr(j,2),16)^r;s+=String.fromCharCode(c);}s=document.createTextNode(s);l.parentNode.replaceChild(s,l);}}catch(e){}})();
                        /* ]]> */
 /*                       </script>');
    */




     /*
ancienne methode + template mail
     public function oldcontactAction()
    {

    	$contact = new Contact();
        $form   = $this->createForm(new ContactType(), $contact);

    	$request = $this->getRequest();
       
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

             if ($form->isValid()) {

               $this->get('session')->getFlashBag()->add('notice', 'Votre Email a été envoyé. merci de nous avoir contacté'); 
            //$this->get('session')->setFlash('notice', 'Votre Email a été envoyé. merci de nous avoir contacté');

                $message = \Swift_Message::newInstance()
                    ->setSubject('Hello Email')
                    ->setFrom('dev.gbe@company.com')
                    ->setTo('recipient@example.com')
                    ->setBody($this->renderView('GbCreationHomeBundle:Contact:email.txt.twig'))
                ;
                $this->get('mailer')->send($message);



                return $this->render('GbCreationHomeBundle:Home:index.html.twig');
            }
        }

        return $this->render('GbCreationHomeBundle:Contact:contact.html.twig',
                array(
                    'form' => $form->createView(),
                ));
    }*/

}
