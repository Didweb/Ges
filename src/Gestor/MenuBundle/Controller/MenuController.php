<?php

namespace Gestor\MenuBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Gestor\MenuBundle\Entity\Menu;

class MenuController extends Controller
{

	
    public function indexAction()
    {
		$menu = new Menu();
		$menu->setEntiMenu($this->container->getParameter('EntiMenu'));
		$entidades = $menu->getEntiMenu();
		
        return $this->render('GestorMenuBundle:Default:index.html.twig',array('entidades' => $entidades));
    }



}
