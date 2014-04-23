<?php
namespace Gestor\MenuBundle\Entity;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

class Menu
{
	private $Nom;
	
	private $grup;
	
	private $crud;

	private $campoorden;
	
	public function __construct() {
        $this->crud 	= new ArrayCollection();
	}

	public function setGrup($grup)
	{
		$this->grup = $grup;
		return $this;
	}

	
	public function getGrup()
	{
		return $this->grup;
	}

	public function setCampoorden($campoorden)
	{
		$this->campoorden = $campoorden;
		return $this;
	}

	
	public function getCampoorden()
	{
		return $this->campoorden;
	}


	public function setNom($nom)
	{
		$this->nom = $nom;
		return $this;
	}

	
	public function getNom()
	{
		return $this->nom;
	}
	

	
	public function setCrud($crud)
	{
		
		$this->crud = $crud;
		return $this;
	}	
	
	
	public function getCrud()
	{
		return $this->crud;
	}
	
	

	
	
}
