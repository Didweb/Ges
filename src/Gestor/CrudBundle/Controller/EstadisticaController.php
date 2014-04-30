<?php

namespace Gestor\CrudBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;


class EstadisticaController extends Controller
{ 
	private $entidades;
	
	public function EstadisticasAction()
	{
	$this->entidades = $this->getEntidades();
	
	return $this->render('GestorCrudBundle:Fijas:index.html.twig',array( 'entidades'  => $this->entidades));
	}


	public function ContarReg($entidad)
	{
		$entidadNameSpace 		= 'GestorCrudBundle:'.ucwords($entidad);
		
		$em = $this->getDoctrine()->getManager();
		
		$qb = $em->createQueryBuilder();
		$qb->select('count(account.id)');
		$qb->from($entidadNameSpace,'account');

		$count = $qb->getQuery()->getSingleScalarResult();
		
		return $count;
		
	}


	private function getEntidades()
	{
		$solicitud = $this->container->getParameter('EntiMenu');
		$separar = explode("|",$solicitud);
		
		$res = array();
		$z = 0;
		for ($n=0;$n<=count($separar)-1;$n++) {
			$separar2 = explode("*",$separar[$n]);
				for ($x=0;$x<=count($separar2)-1;$x++) {
					if($x==1){
						
					$totalReg = $this->ContarReg($separar2[1]);	
					$res[$z] = array(
								'entidad' 	=> $separar2[1],
								'Nreg'		=> $totalReg);
					$z++;
					}
				}
			
			}	
			
		return $res;
	}

}	
