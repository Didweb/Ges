<?php
namespace Gestor\MenuBundle\Entity;

use Symfony\Component\HttpFoundation\Request;

class Menu
{
	private $EnitMenu;
	
	public function getEntiMenu()
	{
		return $this->entimenu;
	}
	
	
	public function setEntiMenu($entimenu)
	{
		$this->entimenu = $entimenu;
        return $this;
	}
	
	
	
	
}
