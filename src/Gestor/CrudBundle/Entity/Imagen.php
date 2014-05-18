<?php

namespace Gestor\CrudBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo; 
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

/**
 * Imagen
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Gestor\CrudBundle\Entity\ImagenRepository")
 */
class Imagen
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
     * @var integer
     * @ORM\Column(name="orden", type="integer")
     */
    private $orden;


    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     * @Gedmo\Slug(fields={"nombre"})
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;



    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=5)
     */
    private $extension;



    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;



	/**
	 * @ORM\ManyToOne(targetEntity="Noticia", inversedBy="imagenes")
	 * @ORM\JoinColumn(name="faena_id", referencedColumnName="id")
	 */ 
	 private $noticia;

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
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

  /**
     * Set orden
     *
     * @param integer $orden
     * @return Imagen
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return integer 
     */
    public function getOrden()
    {
        return $this->orden;
    }

  /**
     * Set noticia
     *
     * @param string $noticia
     * @return Imagen
     */
    public function setNoticia($noticia)
    {
        $this->noticia = $noticia;

        return $this;
    }

    /**
     * Get noticia
     *
     * @return string 
     */
    public function getNoticia()
    {
        return $this->noticia;
    }
    
    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Imagen
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Imagen
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }



    /**
     * Set extension
     *
     * @param string $extension
     * @return Imagen
     */
    public function setExtension()
    {
		$nombredelpath=$this->getFile()->getClientOriginalName();
		$extension	=	explode(".",$nombredelpath);
		$corte		=	count($extension)-1;
		$extension	=	$extension[$corte];	
		
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

		
}
