<?php
namespace GbCreation\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\SimpleXMLElement;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('GbCreationHomeBundle:Home:index.html.twig');
    }

    public function homeAction()
    {
    	$headerSlide = $this->displaySlider("images/slider");

        return $this->container->get('templating')->renderResponse('GbCreationHomeBundle:Home:home.html.twig',array(
        	'headerSlide' => $headerSlide,
        	));
    }

     public function contactAction()
    {
        return $this->render('GbCreationHomeBundle:Home:contact.html.twig');
    }

    public function newsAction()
    {
    	$params = $this->container->getParameter('param');
        $NB_ITEM_TO_GET = $params['MAX_ITEM'];
        $NB_COMMENT_TO_GET = $params['MAX_COMMENT'];
		
		//$rssFeeds = $this->container->getParameter('rssFeeds');
        //$RSS_VIDEO_FEED = $rssFeeds['RSS_VIDEO_FEED'];

        $em = $this->getDoctrine()
                   ->getManager();

        $items = $em->getRepository('GbCreationWallBundle:Item')->getLastItemsByType($NB_ITEM_TO_GET,'Picture');
        $videos = $em->getRepository('GbCreationWallBundle:Item')->getLastItemsByType($NB_ITEM_TO_GET,'Video');
        $comments = $em->getRepository('GbCreationWallBundle:Comment')->getLastComments($NB_COMMENT_TO_GET);


        return $this->container->get('templating')->renderResponse('GbCreationHomeBundle:Home:news.html.twig',array(
        		'items' => $items,
        		'comments' => $comments,
        		'videos' => $videos,
        	));
    }

    public function portfolioAction()
    {
    	$params = $this->container->getParameter('param');
		$rssFeeds = $this->container->getParameter('urls');
       
        $urlBlog = $rssFeeds['EXTERNAL_BLOG'];
        $urlShare = $rssFeeds['SHARE_DATA'];


        return $this->container->get('templating')->renderResponse('GbCreationHomeBundle:Home:portfolio.html.twig',array(
        		'urlBlog' => $urlBlog,
        		'urlShare' => $urlShare,
        	));
    }

    public function decompteAction()
    {
        return $this->container->get('templating')->renderResponse('GbCreationHomeBundle:Home:decompte.html.twig');
    }


	public function getNbHeader($path)
	{
		$nb_fichier = 0;
		if($dossier = opendir($path)){
			while(false !== ($fichier = readdir($dossier))){
				if($fichier != '.' && $fichier != '..' && $fichier != 'index.php'&& $fichier != '@eaDir'){
					//echo '==>' . $fichier .'<br>';  
					$nb_fichier++;
				}
			}
			//echo 'Il y a <strong>' . $nb_fichier .'</strong> fichier(s) dans le dossier<br>';  
			closedir($dossier);
		}
		else{
			 echo 'Le dossier n\' a pas pu être ouvert';
		}
		return 	$nb_fichier;				
	}
	 
	 public function displaySlider($path)
	{
		 $params = $this->container->getParameter('param');


		$slides = array();
		$slidesChecked = array();
		$nb = $this->getNbHeader($path);
		//print_r("il faut afficher ".NB_SLIDE." sur ".$nb." fichiers");
		if($nb <= $params['NB_SLIDE']){
			for($i=0;$i<$params['NB_SLIDE'];$i++){
				$slides[$i] = 'header'.$i.'.jpg';
			}
		}
		else{
			for($i=0;$i<$params['NB_SLIDE'];$i++){
				$toFind = true;
				while($toFind){
					// si 8 fichiers random de 0 a 7
					$z = mt_rand(0, $nb-1);	
					if(!array_key_exists($z, $slidesChecked)) {		
						$slidesChecked[$z]="true";
						$toFind = false;	
					}
				}
				$slides[$i] = 'header'.$z.'.jpg';
			}
		}
		//print_r($slides);
		//print_r($slidesChecked);
		return $slides;
	}


	function feedRssAction()
	{
		$logger = $this->get('logger');
		$request = $this->container->get('request');

		$logger->info('[feedRssAction] recuperation des infos des flux rss / AJAX');
        if($request->isXmlHttpRequest())
        {
            $rss = '';
            $rss = $request->request->get('rss');

            if($rss != ''){
            	if($rss == "video" || $rss == 'blog'){
            	 $logger->info('[feedRssAction] recuperation des videos ou blogs');
            	 $items = $this->FeedRssParser($rss);
	            }
	            else{
	            	 $logger->info('[feedRssAction] Erreur. ni video ni blog');
	            	 $items = 'Aucune info sur ce flux rss';
	            }

	             return $this->container->get('templating')->renderResponse('GbCreationHomeBundle:Rss:item.display.html.twig', array(
	                'items' => $items,
	             ));
	         } //rss != ''
	         else{
	            	 $logger->info('[feedRssAction] argument fourni en paramètre ?rss= doit etre vide ');
	            	 $items = 'Aucune info sur ce flux rss';
	         }
        } //isXmlHttpRequest
		else {
            $logger->info('[feedRssAction] rqt non ajax redirect vers index avec affichage par defaut');            
            return $this->indexAction();
        }
	}


	function FeedRssParser($rss){
		$logger = $this->get('logger');
		
		$rssFeeds = $this->container->getParameter('rssFeeds');
		if($rss == "video"){
			$urlFeed = $rssFeeds['RSS_VIDEO_FEED'];
        	$nbItem = $rssFeeds['RSS_VIDEO_NB_ITEM'];
        }
        else if($rss == "blog"){
			$urlFeed = $rssFeeds['RSS_BLOG_FEED'];
        	$nbItem = $rssFeeds['RSS_BLOG_NB_ITEM'];
        }

	 	$logger->info('[FeedRssParser]  Feed rss sur  ['.$urlFeed.'] ');
	 	$retour = 'Aucune info sur ce flux rss';

		try {	
		    // is cURL installed yet?
		    if (!function_exists('curl_init')){
		    	$logger->error('Sorry cURL is not installed!');
		        return $retour;
		    }
		 
		    // OK cool - then let's create a new cURL resource handle
		    $ch = curl_init();

		    if (FALSE === $ch){
		    	$logger->error('[FeedParser] failed to initialize');
		    	throw new Exception('failed to initialize');
		    }
		        	 
		    // Now set some options (most are optional) 
		    // Set URL to download
		    curl_setopt($ch, CURLOPT_URL, $urlFeed);
		    // Include header in result? (0 = yes, 1 = no)
		    curl_setopt($ch, CURLOPT_HEADER, 0);
		    // Should cURL return or print out the data? (true = return, false = print)
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		    // Timeout in seconds
		    //curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		 
			$header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
			$header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
			$header[] = "Cache-Control: max-age=0";
			$header[] = "Connection: keep-alive";
			$header[] = "Keep-Alive: 300";
			$header[] = "Accept-Charset: utf-8";
			$header[] = "Accept-Language: fr"; // Langue fr
			$header[] = "Pragma: "; // Simule un navigateur
			 
			$useragent = 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0'; // Pour se faire passer pour Firefox
			curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		    // Download the given URL, and return output
		    $logger->info('[FeedRssParser] execution curl');
		    $contenu = curl_exec($ch);
		    $logger->info('[FeedRssParser] execution curl effectuée');

			//var_dump($contenu);
			//die();

			if (FALSE === $contenu){
				$logger->error('[FeedParser] Erreur Curl [' . curl_error($ch).']<br>');
				$logger->error('[FeedParser] Erreur Curl [' . curl_errno($ch).']<br>');
				//curl_error($ch);
				return $retour;
			}

			$rss_doc = new SimpleXmlElement($contenu, LIBXML_NOCDATA);
			//var_dump($rss_doc);
			//die();

			if(isset($rss_doc->channel))
			{
			    $retour = $this->parse_rss($rss_doc,$nbItem);
			}  
			elseif(isset($rss_doc->entry))
			{
			    $retour = $this->parse_atom($rss_doc,$nbItem);
			}

		    // Close the cURL resource, and free system resources
			curl_close($ch);

		} catch(Exception $e) {
			$logger->error('Exception........ GBE  code['.$e->getCode().'] msg['.$e->getMessage().']');
			$retour = "Erreur dans la récupération du flux";
			//echo 'Exception........ GBE  code['.$e->getCode().'] msg['.$e->getMessage().']';
			//trigger_error(sprintf('Curl failed with error #%d: %s',$e->getCode(), $e->getMessage()),E_USER_ERROR);
		}
	 
	    return $retour;
	}

	

	function parse_rss($doc,$nbItem)
	{
	    $nbI = 0;
	    // Pour chaque element...
	    foreach($doc->channel->item as $item)
	    {
		//$retour .= '<span class="author">'.$item->title.'</span>: &ldquo;<a href="'.$item->link.'">'.substr($item->description,0,30).' ...&rdquo;</a>';
	        if($nbI < $nbItem){
		    $items = array (
		      "title" => $item->title,
		      "link" => $item->link,
		      //"description" => substr($item->description,0,30),
		    );
		    $retour[] = $items; 
		    $nbI++;
		}
		else{
		    break;
		}
	    }
	    return $retour;
	}  

	function parse_atom($doc,$nbItem)
	{
	     $nbI = 0;
	    // Pour chaque element...
	    foreach($doc->entry as $item)
	    {
		 if($nbI < $nbItem){
		    $items = array (
		      "title" => $item->title,
		      "link" => $item->link->attributes(),
		      //"description" => substr($item->content,0,30),
		    );
		    $retour[] = $items; 
		    $nbI++;
		}
		else{
		    break;
		}
	    }
	    return $retour;
	}

}
