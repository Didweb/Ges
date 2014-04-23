<?php
namespace Gestor\CrudBundle\Paginacion;


use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

/*
 * Paginacion
 * */
class paginacion 
{

	private $pagina;
	
	private $pagpaginador;
	
	private $datosmatriz;
	
	private $totalregistros;

	private $totalpaginas;
	
	private $regpagina;
	
	private $primera;
	
	private $ultima;
	
	private $previa;
	
	private $proxima;
	
	public function inicio($session,$datos,$contV,$pagina,$rpag=10,$pagpaginador=3)
	{
		$session->start();
		$request = Request::createFromGlobals();

		if (!is_array($datos)){
			echo "No es un array [paginacion]";
			exit;
			}
		
		$this->setTotalregistros($datos,$contV);
		$this->setRegpagina($rpag);
		$this->setTotalpaginas();
		$this->setPagina($session,$pagina);
		$this->setPrimera();
		$this->setUltima($this->getTotalpaginas());
		$this->setPrevia();
		$this->setProxima();
		$this->setPagpaginador($pagpaginador);
		$this->setDatosmatriz($datos);
		
	}
	
	public function setPagina($session,$pagina)
	{
		
		$session->start();
		$pagsolicitada = $pagina;
		if($pagsolicitada>$this->getTotalpaginas()){
			$pagsolicitada = $this->getTotalpaginas(); }
			
		if($session->has('pagina')){
			
			if($pagsolicitada==''){
				$session->set('pagina',1); } 
				else {
				$session->set('pagina',$pagsolicitada); }
			
			}
			else{
			$session->set('pagina',1); }
		

		
		$this->pagina = $session->get('pagina');
        return $this;
	}


    public function getPagina()
    {
        return $this->pagina;
    }
  
    
	public function setPrimera()
	{
		$this->primera = 1;
        return $this;
	}


    public function getPrimera()
    {
        return $this->primera;
    }


	public function setPrevia()
	{
		$this->previa = $this->pagina-1;
        return $this;
	}


    public function getPrevia()
    {
        return $this->previa;
    }
    

	public function setProxima()
	{
		$this->proxima = $this->pagina+1;
        return $this;
	}


    public function getProxima()
    {
        return $this->proxima;
    }
    
    
	public function setUltima()
	{
		$this->ultima = $this->getTotalpaginas();
        return $this;
	}


    public function getUltima()
    {
        return $this->ultima;
    }
    
    
	public function setPagpaginador($pagpaginador)
	{	
		$rac='';
		for($n=1;$n<=$pagpaginador;$n++){
				$op=$this->getPagina()-$n;
				if($op<=0) {$op='';}
				
				$rac.=$op; }
				
		$rac = str_split($rac);
		sort($rac);
		$rac = implode("", $rac);
		
		$rac.=$this->getPagina();
		
		for($n=1;$n<=$pagpaginador;$n++){
			
			$op=$this->getPagina()+$n;
			if($op>$this->getTotalpaginas()) {$op='';}
			$rac.=$op; }
		
		$this->pagpaginador = str_split($rac);
        return $this;
	}


    public function getPagpaginador()
    {
        return $this->pagpaginador;
    }   
    
   
	public function setDatosmatriz($datos)
	{
		$grupo1 =($this->getRegpagina()*4);
		if($this->pagina==1)
			{$inicio=0;}
			else{
				$inicio = ($grupo1*$this->pagina)-$grupo1; }
		
		$final	= $grupo1;
		$salida = array_slice($datos, $inicio , $final);
		$this->datosmatriz = $salida;
        return $this;
	}


    public function getDatosmatriz()
    {
        return $this->datosmatriz;
    } 
   
    
	public function setTotalregistros($totalregistros,$contV)
	{
		$totalregistros = count($totalregistros)/$contV;
		$this->totalregistros = $totalregistros;
        return $this;
	}


    public function getTotalregistros()
    {
        return $this->totalregistros;
    }     


	public function setRegpagina($regpagina)
	{
		$this->regpagina = $regpagina;
        return $this;
	}


    public function getRegpagina()
    {
        return $this->regpagina;
    }     


	public function setTotalpaginas()
	{
		$totalpaginas = ceil($this->getTotalregistros()/$this->getRegpagina());
		$this->totalpaginas = $totalpaginas;
        return $this;
	}


    public function getTotalpaginas()
    {
        return $this->totalpaginas;
    }     



}
