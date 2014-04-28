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





    public function getAbsolutePath(Container $container)
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir($container).'/'.$this->path;
    }

    public function getWebPath(Container $container)
    {
        return null === $this->path
            ? null
            : $this->getUploadDir($container).'/'.$this->path;
    }

    protected function getUploadRootDir(Container $container)
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir($container);
       
    }

    protected function getUploadDir($container)
    {	
		$this->container = $container;
        $carpeta = $this->container->getParameter('img_carpeta');
        return $carpeta;
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

	public function eliminararchivo(Container $container,$nomruta)
		{
		$ruta=$this->getUploadDir($container)."/g/".$nomruta;
		$rutap=$this->getUploadDir($container)."/p/".$nomruta;
			if (file_exists($ruta)) {
				unlink($ruta);
				unlink($rutap); }
		}	




	public function upload(Container $container,$size_ancho,$size_alto,$ultimo)
		{
			$this->container = $container;
			$img_ancho_p 	= $this->container->getParameter('img_ancho_p');
			$img_alto_p 	= $this->container->getParameter('img_alto_p');
			$img_ancho_g 	= $this->container->getParameter('img_ancho_g');
			$img_alto_g 	= $this->container->getParameter('img_alto_g');
		    
		   if (null === $this->getFile()) {
			return;
		    }
			//--->> Sacamos extension
			$nombredelpath	= $this->getFile()->getClientOriginalName();
			
			
		
		    //Subimos el archivo con el nuevo nombre
		    $this->getFile()->move($this->getUploadRootDir($container).'/g/',$ultimo);
		
		   
			//$pImageOrigen=$this->getFile()->getClientOriginalName();
			$tmpname	= $this->getUploadRootDir($container)."/g/".$ultimo;	
			$save_dir_p	= $this->getUploadRootDir($container).'/p/';
			$save_dir_g	= $this->getUploadRootDir($container).'/g/';
			$save_name	= $ultimo;
			
			$this->img_resize( $tmpname, $img_ancho_p,$img_alto_p, $save_dir_p, $save_name );
			$this->img_resize( $tmpname, $img_ancho_g,$img_alto_g, $save_dir_g, $save_name );
		
		    // limpia la propiedad «file» ya que no la necesitas más
		    $this->file = null;
		}


	public function img_resize( $tmpname, $size_ancho,$size_alto, $save_dir, $save_name )
	    {
		$size = $size_ancho;	
	    $save_dir .= ( substr($save_dir,-1) != "/") ? "/" : "";
	    $gis       = GetImageSize($tmpname);
	    $type       = $gis[2];
	   
	    switch($type)
		{
		case "1": $imorig = imagecreatefromgif($tmpname); break;
		case "2": $imorig = imagecreatefromjpeg($tmpname);break;
		case "3": $imorig = imagecreatefrompng($tmpname); break;
		default:  $imorig = imagecreatefromjpeg($tmpname);
		}

		$x = imageSX($imorig);
		$y = imageSY($imorig);
		
			//if($gis[0]<$gis[1])
		if($gis[0] <= $size)
				{
				$av = $x;
				$ah = $y;
				}
				else
				{
				//aplicar el tamaño de alto en caso de que la foto sea mas alta que larga	
				if($gis[0]<$gis[1])
				{$size=$size_alto;}
				
				$yc = $y*1.3333333;
				$d = $x>$yc?$x:$yc;
				$c = $d>$size ? $size/$d : $size;
				$av = $x*$c;        
				$ah = $y*$c;        
				}  
		
		$im = imagecreate($av, $ah);
		$im = imagecreatetruecolor($av,$ah);
			//para fondo blanco
			$blanco = imagecolorallocate($im, 255, 255, 255);
			imagefill($im, 0, 0, $blanco);
			//fin para fondo blanco
	    if (imagecopyresampled($im,$imorig , 0,0,0,0,$av,$ah,$x,$y))
		if (imagejpeg($im, $save_dir.$save_name))
		    return true;
		    else
		    return false;
	    }


public function borrarArchivos($container,$nomruta)
	{
	$ruta=$this->getUploadDir($container)."/g/".$nomruta;
	$rutap=$this->getUploadDir($container)."/p/".$nomruta;
			if (file_exists($ruta)) {
				unlink($ruta);
				unlink($rutap); }	
	return 0;
	}
		
}
