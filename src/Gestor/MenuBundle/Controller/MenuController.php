<?php

namespace Gestor\MenuBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Gestor\MenuBundle\Entity\Menu;

class MenuController extends Controller
{

	
    public function indexAction(Request $request)
    {
		$separado = $this->componerAction();
		$donde = explode('/',$request->server->get('PHP_SELF'));
		$donde = $donde[count($donde)-1];
        return $this->render('GestorMenuBundle:Default:menu.html.twig',
							array(	'resultado'	=> $separado,
									'donde' => $donde));
    }

	
	public function componerAction()
	{
		
		$solicitud = $this->container->getParameter('EntiMenu');
		$separar = explode("|",$solicitud);
		
		
		$tot = array();
		
		for ($n=0;$n<=count($separar)-1;$n++) {
				$entity[$n] = new Menu();
				$tot[$n] 	= explode("*",$separar[$n]);
				$elcrud 	= str_split($tot[$n][2]);
				
				$entity[$n]->setGrup($tot[$n][0]);
				$entity[$n]->setNom($tot[$n][1]);
				$entity[$n]->setCampoorden($tot[$n][2]);
				$entity[$n]->setCrud($elcrud);
			
			}

		return $entity;	
	}

}
