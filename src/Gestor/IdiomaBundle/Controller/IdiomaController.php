<?php

namespace Gestor\IdiomaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IdiomaController extends Controller
{
	

	
	
	
    public function cambioAction(Request $request, $newlang)
    {
		
		$session 	= $request->getSession();	
		$ensesion	= $session->get('_locale');
	
	
		$idioma		= $request->query->get('newlang');
		$pillabots = $this->_bot_detected();
		if($pillabots==TRUE) { 
			$id = $this->getLocales();
			$idioma_final=$id[0]; 
			$request->setLocale($idioma_final);
		}
		else
		{
			if($idioma=='' && $ensesion=='') {
				$idioma=$request->getLanguages();
				
				$tot=count($idioma);
					
				if($tot>1){
					$idioma_final = $request->setLocale($idioma[1]);}
					else{
					$idioma_final = $request->setLocale($idioma[0]);}	
				
				
				
					
				$request->setLocale($idioma_final);
				$this->get('session')->set('_locale', $idioma_final);
				}
			elseif($idioma!=''){
				$this->get('session')->set('_locale', $idioma);
				$request->setLocale($idioma);
				}
			elseif($ensesion!=''){
				$request->setLocale($ensesion);
				}
		}		
		
		

    $request = $this->getRequest();
    $referer = $request->headers->get('referer');
    $locales = implode('|',$this->getLocales());
    $url = preg_replace('/\/('.$locales.')\//', '/'.$newlang.'/', $referer, 1);
    
    return $this->redirect($url);
    
    }

	private function getLocales()
	{
		
        $losidiomas = $this->container->getParameter('idiomas_crud');
        $losidiomas = explode(',',$losidiomas);
		return $losidiomas;
	}


	public function _bot_detected() {

		  if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
			return TRUE;
		  }
		  else {
			return FALSE;
		  }

		}


	public function MenuIdiomaAction($actual)
	{
		$idiomas = $this->getLocales();
		return $this->render('GestorIdiomaBundle:Default:index.html.twig',
						array(
						'idiomas'		=> $idiomas,
						'idiomaactual'	=>$actual));
		
	}

}
