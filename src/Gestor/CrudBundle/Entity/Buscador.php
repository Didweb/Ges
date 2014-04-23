<?php
namespace Gestor\CrudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


class Buscador
{
	
	private $dato;


    public function setDato($dato)
    {
        $this->dato = $dato;

        return $this;
    }

    
    public function getDato()
    {
        return $this->dato;
    }

            	
}	
