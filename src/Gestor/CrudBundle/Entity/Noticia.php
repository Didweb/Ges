<?php

namespace Gestor\CrudBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Noticia
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Noticia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="texto", type="text")
     */
    private $texto;


    /**
     * @var string
     *
     * @ORM\Column(name="autor", type="string")
     */
    private $autor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creacion", type="datetime")
     */
    private $creacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modificacion", type="datetime")
     */
    private $modificacion;


	/**
	 * @ORM\OneToMany(targetEntity="Imagen", mappedBy="noticia", cascade={"all"})
	 * 
	 */ 
	 protected $imagenes;

	public function __construct()
	{
		$this->imagenes = new ArrayCollection();
	}


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     * @return Noticia
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }


    /**
     * Set autor
     *
     * @param string $autor
     * @return Noticia
     */
    public function setAutor($autor)
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * Get autor
     *
     * @return string 
     */
    public function getAutor()
    {
        return $this->autor;
    }


    /**
     * Set texto
     *
     * @param string $texto
     * @return Noticia
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string 
     */
    public function getTexto()
    {
        return $this->texto;
    }

    /**
     * Set creacion
     *
     * @param \DateTime $creacion
     * @return Noticia
     */
    public function setCreacion($creacion)
    {
        $this->creacion = $creacion;

        return $this;
    }

    /**
     * Get creacion
     *
     * @return \DateTime 
     */
    public function getCreacion()
    {
        return $this->creacion;
    }

    /**
     * Set modificacion
     *
     * @param \DateTime $modificacion
     * @return Noticia
     */
    public function setModificacion($modificacion)
    {
        $this->modificacion = $modificacion;

        return $this;
    }

    /**
     * Get modificacion
     *
     * @return \DateTime 
     */
    public function getModificacion()
    {
        return $this->modificacion;
    }
}
