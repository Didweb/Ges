<?php

namespace Claror\FeinesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
/**
 * Feina
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Claror\FeinesBundle\Entity\FeinaRepository")
 */
class Feina
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
     * @Gedmo\Translatable
     * @ORM\Column(name="titulo", type="string", length=255)
     */
    private $titulo;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="etiqueta", type="string", length=255)
     */
    private $etiqueta;

    /**
     * @var string
     * @Gedmo\Translatable
     * @ORM\Column(name="texto", type="string", length=255)
     */
    private $texto;


	/**
	 *  @Gedmo\Locale
	 */ 
	 private $locale;
	 
	 
	 public function setTranslatableLocale($locale)
	 {
			$this->locale = $locale;
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
     * @return Feina
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
     * Set etiqueta
     *
     * @param string $etiqueta
     * @return Feina
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

    /**
     * Set texto
     *
     * @param string $texto
     * @return Feina
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
}
