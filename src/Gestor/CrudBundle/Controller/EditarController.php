<?php

namespace Gestor\CrudBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

use Gestor\CrudBundle\Entity\Imagen;

class EditarController extends Controller
{       
	
	
	public function nuevoAction($entidad)
	{
	
		$edit_form = $this->formulario($entidad);
	
		return $this->render('GestorCrudBundle:Crud:crear.html.twig',
						array(	'edit_form' => $edit_form->createView(),
								'entidad'	=> $entidad
								));	
	}

	/*
	 * 
	 * Creamos Formulario.
	 * 
	 */ 
	public function formulario($entidad)
	{
		$entidadNameSpace 	= 'Gestor\CrudBundle\Entity\\'.ucwords($entidad);
		$entidadNameSpaceType 	= 'Gestor\CrudBundle\Form\\'.ucwords($entidad).'Type';
		
		$entity = new $entidadNameSpace();
		$form   = $this->createForm(new $entidadNameSpaceType(), $entity);
        
        return $form;
	}


	/*
	 * 
	 * Crear nuevo registro
	 * 
	 */ 
    public function crearAction(Request $request,$entidad)
    {
		$entidadNameSpace 		= 'Gestor\CrudBundle\Entity\\'.ucwords($entidad);
		$entidadNameSpaceType 	= 'Gestor\CrudBundle\Form\\'.ucwords($entidad).'Type';
		
        $entity = new $entidadNameSpace();
        $form = $this->createForm(new $entidadNameSpaceType(),$entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
			$this->get('session')->getFlashBag()
						->add('editar_ok',
						'Se ha insertado el nuevo registro');
            return $this->redirect($this->generateUrl('gestor_editar_registro', 
											array(
											'entity'	=> $entity,
											'entidad'	=> $entidad,
											'id' 		=> $entity->getId())
											));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }



	/**
	 * 
	 * Editar nuevo registro
	 * 
	 */ 
    public function editarAction(Request $request,$entidad,$id)
    {
		$entidadNameSpace 		= 'GestorCrudBundle:'.ucwords($entidad);
		$entidadNameSpaceType 	= 'Gestor\CrudBundle\Form\\'.ucwords($entidad).'Type';
		
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($entidadNameSpace)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se ha encontrado la entidad ['.$entidad.'] ');
        }

		
        $edit_form = $this->createForm(new $entidadNameSpaceType(), $entity);

        return $this->render('GestorCrudBundle:Crud:editar.html.twig',
							array(	'entity' 		=> $entity,
									'entidad'		=> $entidad,
									'edit_form'		=> $edit_form->createView(),
									'NecesitaImg'	=> $this->NecesitaImg(ucwords($entidad))
									) );
        
    }


	/**
	 * 
	 * Actualizar nuevo registro
	 * 
	 */ 
    public function actualizarAction(Request $request,$entidad,$id)
    {
		$entidadNameSpace 		= 'GestorCrudBundle:'.ucwords($entidad);
		$entidadNameSpaceType 	= 'Gestor\CrudBundle\Form\\'.ucwords($entidad).'Type';
		
		$em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($entidadNameSpace)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se ha encontrado la entidad ['.$entidad.'] ');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new $entidadNameSpaceType(), $entity);
        $editForm->bind($request);	
		
		
		  if ($editForm->isValid()) {
		    $em->persist($entity);
            $em->flush();
			$this->get('session')->getFlashBag()->add('editar_ok', 'Registro actualizado con éxito.');
			return $this->redirect($this->generateUrl('gestor_editar_registro', 
														array(
														'id' => $id,
														'entidad'=>$entidad)));
		  }
		
	}	


	/**
	 * 
	 * Actualizar nuevo registro
	 * 
	 */ 
    public function eliminarAction(Request $request,$entidad,$id,$ok)
    {
		$entidadNameSpace 		= 'GestorCrudBundle:'.ucwords($entidad);
		$entidadNameSpaceType 	= 'Gestor\CrudBundle\Form\\'.ucwords($entidad).'Type';
	
		$em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($entidadNameSpace)->find($id);
	
		if($ok=='no'){
			return $this->render('GestorCrudBundle:Crud:borrar_1.html.twig',
									array(
									'entidad'  => $entidad,
									'id'	   => $entity->getId()	
									
									));
		} elseif($ok=='si'){
			
            if (!$entity) {
                throw $this->createNotFoundException('Entidad '.$entidad.' no encontrada en [eliminarAction opcion ok:si]');
            }
			$this->get('session')->getFlashBag()->add('listado_elimina', 'Se ha eliminado el registro de froma correcta.');
           
			$entity_foto = $em->getRepository('GestorCrudBundle:Imagen')->findByTodasLasFotos($id,$entidad);
			
		    $entity_f = new Imagen;
		    $container =$this->container;
            foreach ($entity->getImagenes() as $nom){
				$nomruta = $nom->getSlug().'.'.$nom->getExtension();
				$entity_f->borrarArchivos($container,$nomruta); }
           
           
            $em->remove($entity);
            $em->flush();
			return $this->redirect($this->generateUrl('gestor_listar_orden_col', 
														array(
														'pagina'	=> 1,
														'orden'		=> 'DESC',
														'campo'		=> $this->SacaCampoOrder(ucwords($entidad)),
														'entidad'	=>$entidad)));

		}
	}
	



	private function SacaCampoOrder($nom)
	{
		$solicitud = $this->container->getParameter('EntiMenu');
		$separar = explode("|",$solicitud);
		
		$res = 'id';
		
		for ($n=0;$n<=count($separar)-1;$n++) {
			$separar2 = explode("*",$separar[$n]);
				for ($x=0;$x<=count($separar2)-1;$x++) {
					
					if($separar2[$x]==$nom)
						{$res = $separar2[2];}
				}
			
			}	
			
		return $res;
	}
	
	private function NecesitaImg($nom)
	{
		$nom = strtolower($nom);
		$res = 'no';
		$solicitud = $this->container->getParameter('EntidadesConImg');
		$separar = explode("*",$solicitud);
		
		
		for ($n=0;$n<=count($separar)-1;$n++) {
			if($separar[$n]==$nom)
				{$res = 'si';}
		}
			
				
			
		return $res;
	}
	
}
