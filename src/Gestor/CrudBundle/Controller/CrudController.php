<?php

namespace Gestor\CrudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class CrudController extends Controller
{
    public function listarAction($entidad)
    {
		$entidad = ucwords($entidad);
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('GestorCrudBundle:'.$entidad)->findAll();

		if (!$entity) {
            throw $this->createNotFoundException('Entidad ['.$entidad.'] no encontrada [listarAction.CrudController].');
			}
		
		
		$mimebros = $this->MiembrosLista($entidad);
     
		$n=0;
		foreach ($mimebros as $n=>$v){
			
				$valores[$n] = array('nommet'	=> 'get'.$mimebros[$n]['campo'],
									'forma'		=> $mimebros[$n]['formato'],
									'valor'		=> $mimebros[$n]['valor']); 
			
		
			$n++;
			
			}
		
		$contV = count($valores)-1;
		$en = array();
		$n=0;
		$v=1;
		foreach ($entity as $valor){
			if($v==$contV){$v=1;} 
				for($b=$v;$b<=$contV;$b++){
						$en[$n]= array($b => array('campo'=>$valor->$valores[$b]['nommet'](),
												   'formato'=>	$valores[$b]['forma'],
												   'valor'=>	$valores[$b]['valor']
													));
						
					
					$n++;}
					
			
			$v++;
			}
     
		
     
        return $this->render('GestorCrudBundle:Crud:listar.html.twig',
							array(	'entity' 		=> $en,
									'nomentidad'	=> $entidad,
									'mimebros'		=> $mimebros));
    }

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
					
				}

								
			}
					
		}

		return $MiLi;	
	}
}
