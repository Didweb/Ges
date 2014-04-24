<?php

namespace Gestor\CrudBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;



class EditarController extends Controller
{       
	
	
	public function editaAction($entidad)
	{
	
		$edit_form = $this->formulario($entidad);
	
		return $this->render('GestorCrudBundle:Crud:editar.html.twig',
						array(	'edit_form' => $edit_form->createView()
								));	
	}

	/************************************************************
	 * 
	 * Creamos Formulario.
	 * 
	 ************************************************************ */ 
	public function formulario($entidad)
	{
		$Mayentidad 	= 'Gestor\CrudBundle\Entity\\'.ucwords($entidad);
		$MayentidadType	= 'Gestor\CrudBundle\Form\\'.ucwords($entidad).'Type';
		
		$LaEntidad = new $Mayentidad();
		$form   = $this->createForm(new $MayentidadType(), $LaEntidad);
        
        return $form;
	}


}
