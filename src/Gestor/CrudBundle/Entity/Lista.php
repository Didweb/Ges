<?php
namespace Gestor\CrudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class Lista
{
	
	private $nomentidad;

	private $id;

	private $campo;

	private $formato;
	
	private $valor;


    public function setNomentidad($nomentidad)
    {
        $this->nomentidad = $nomentidad;

        return $this;
    }

    
    public function getNomentidad()
    {
        return $this->nomentidad;
    }


    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    
    public function getId()
    {
        return $this->id;
    }



    public function setCampo($campo)
    {
        $this->campo = $campo;

        return $this;
    }

    
    public function getCampo()
    {
        return $this->campo;
    } 


    public function setFormato($formato)
    {
        $this->formato = $formato;

        return $this;
    }

    
    public function getFormato()
    {
        return $this->formato;
    } 


    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    
    public function getValor()
    {
        return $this->valor;
    }             	
}	
