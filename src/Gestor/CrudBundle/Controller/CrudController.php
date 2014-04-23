<?php

namespace Gestor\CrudBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

use Gestor\CrudBundle\Entity\Lista;

class CrudController extends Controller
{
    public function listarAction(Request $request,$entidad,$pagina,$orden='ASC',$campo='')
    {
		$session = new Session();
		$session->start();	
		
		$entidad = ucwords($entidad);
		
		$entity = $this->getConsulta($session,$entidad,$orden,$campo);
		//$em = $this->getDoctrine()->getManager();
		//$entity = $em->createQuery('SELECT a FROM   GestorCrudBundle:'.$entidad.' a')->getResult();
            
		//$entity = $em->getRepository('GestorCrudBundle:'.$entidad)->findAll();


		if (!$entity) {
            throw $this->createNotFoundException('Entidad ['.$entidad.'] no encontrada [listarAction.CrudController].');
			}
		$mimebros = $this->MiembrosLista($entidad);
		
		$n=0;
		foreach ($mimebros as $n=>$v){
			
				$valores[$n] = array('nommet'	=> 'get'.$mimebros[$n]['campo'],
									'forma'		=> $mimebros[$n]['formato'],
									'valor'		=> $mimebros[$n]['valor']);
									
				$nomcampos[$n]=	$mimebros[$n]['campo'];				 
				$n++; }



		$contV = count($valores)-1;
		$en = array();
		$n=0;
		$v=1;
		foreach ($entity as $valor){
			
				if($v==$contV) {$v=1;}
				 
					for($b=1;$b<=$contV;$b++){
						$en[$n] = new Lista();
						$en[$n]->setNomentidad($entidad);
						$en[$n]->setId($valor->getId());
						$en[$n]->setCampo($valor->$valores[$b]['nommet']());
						$en[$n]->setFormato($valores[$b]['forma']);
						$en[$n]->setValor($valores[$b]['valor']);
						
					 $n++;}
			$v++;
		}
		
		
		$paginacion = $this->get('pagi');
		$paginacion->inicio($session,$en,$contV,$pagina,$rpag=3,$pagpaginador=2);

        return $this->render('GestorCrudBundle:Crud:listar.html.twig',
							array(	'entity' 		=> $paginacion->getDatosmatriz(),
									'nomentidad'	=> $entidad,
									'mimebros'		=> $mimebros,
									'nomcampos'		=> $nomcampos,
									'datospag'		=> $paginacion,
									'orden'			=> $session->get('orden'),
									'contraorden'	=> $session->get('contraorden'),
									'campo'			=> $campo
									));
    }

	
	/***********************************************************
	 * 
	 * 	Creamos las consultas segun se oportuno para consultas de listado
	 * COnsultas con busquedas o ordenar filtros.
	 * 
	 * 
	 ************************************************************ */ 
	public function getConsulta($session,$entidad,$orden,$campo)
	{
		$session->start();	
		$ordenes = $this->getOrden($session,$orden);
		
		if($campo==''){
			$em = $this->getDoctrine()->getManager();
			$entity = $em->createQuery('SELECT a FROM   GestorCrudBundle:'.$entidad.' a')->getResult();
			
			} else{
			$em = $this->getDoctrine()->getManager();
			$entity = $em->createQuery('SELECT a FROM   GestorCrudBundle:'.$entidad.' a 
										ORDER BY  a.'.$campo.' '.$ordenes['orden'].'
										')->getResult();	
			
			}
		
		return $entity ;
		
	}

	public function getOrden($session,$orden)
	{
		$session->start();	
		
		$orden = $orden;
		
		if($orden=='ASC'){
			$contraorden='DESC';
			} elseif($orden=='DESC'){
			$contraorden='ASC';
			}
		
		if($session->has('orden')){
			$session->set('orden',$orden); 
			$session->set('contraorden',$contraorden); 
			
			} elseif ($orden!=$session->get('orden')) {
			$session->set('orden',$orden);
			$session->set('contraorden',$contraorden); 
			}
			
			return $ordenes=array('orden'=>$orden,'contraorden'=>$contraorden);
	}

	/***********************************************************
	 * 
	 * 	Sacamos los valores del parametros de configuracion para
	 * determinar el nombre de campo, formato y valores
	 * 
	 ************************************************************ */
	public function MiembrosLista($entidad)
	{
		
		$solicitud = $this->container->getParameter('MiemLis');
		$separar = explode("|",$solicitud);
		
		$tot = array();
		for ($n=0;$n<=count($separar)-1;$n++){
			
			$tot = explode("*",$separar[$n]);
			
			for ($x=0;$x<=count($tot)-1;$x++){
				
				if($tot[0]==$entidad){
					$desgrana = explode('/',$tot[$x]);
					if(count($desgrana)>1){
						
						$desgrana2 = explode('-',$desgrana[1]);
						if(count($desgrana2)>1  ){
							$MiLi[$x]= array('campo' => ucwords($desgrana[0]),
											 'formato' => $desgrana2[0],
											 'valor' => $desgrana2[1]);
									 }else {
										 
									$MiLi[$x]= array('campo' => ucwords($desgrana[0]),
													 'formato' => $desgrana[1],
													 'valor' => 'nada');	 
										}
					}else
					{
					$MiLi[$x]= array('campo' => ucwords($desgrana[0]),
									 'formato' => 'nada',
									 'valor' => 'nada'); }
					
				} } }

		return $MiLi;	
	}
}
