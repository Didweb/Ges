<?php

namespace Claror\FeinaBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo; 
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Faena
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Claror\FeinaBundle\Entity\FaenaRepository")
 */
class Faena
{
    /**
     * @var string
     * @Gedmo\Slug(fields={"titulo"}, updatable=false, separator="-")
     * @ORM\Column(length=32, unique=true)
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="texto", type="text")
     */
    private $texto;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="etiqueta", type="string", length=255)
     */
    private $etiqueta;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(name="updated", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;


	/**
	 * @ORM\ManyToOne(targetEntity="Categoria", inversedBy="faenas")
	 * @ORM\JoinColumn(name="categoria_id", referencedColumnName="id")
	 * @Assert\NotNull(message="Se ha de especificar una categoría.")
	 */ 
	 private $categoria;

	/**
	 * @ORM\ManyToOne(targetEntity="Fabricante", inversedBy="faenas")
	 * @ORM\JoinColumn(name="fabricante_id", referencedColumnName="id")
	 */ 
	 private $fabricante;


	/**
	 * @ORM\OneToMany(targetEntity="Imagen", mappedBy="imagen")
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
     * Set categoria
     *
     * @param string $categoria
     * @return Faena
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return string 
     */
    public function getCategoria()
    {
        return $this->categoria;
    }


    /**
     * Set fabricante
     *
     * @param string $fabricante
     * @return Faena
     */
    public function setFabricante($fabricante)
    {
        $this->fabricante = $fabricante;

        return $this;
    }

    /**
     * Get fabricante
     *
     * @return string 
     */
    public function getFabricante()
    {
        return $this->fabricante;
    }


    /**
     * Set titulo
     *
     * @param string $titulo
     * @return Faena
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
     * Set texto
     *
     * @param string $texto
     * @return Faena
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
     * Set etiqueta
     *
     * @param string $etiqueta
     * @return Faena
     */
    public function setEtiqueta($etiqueta)
    {
        $this->etiqueta = $etiqueta;

        return $this;
    }

    /**
     * Get etiqueta
     *
     * @return string 
     */
    public function getEtiqueta()
    {
        return $this->etiqueta;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }
}
