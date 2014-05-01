<?php

namespace Gestor\CrudBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

use Gestor\CrudBundle\Entity\Imagen;

class EditarController extends Controller
{       
	private $creacionEdit=0;
	private $modificacionEdit=0;
	
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
            
            $this->BuscaMetodos($entity,'create');
            
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

		$this->BuscaMetodos($entity,'edit');
		
        return $this->render('GestorCrudBundle:Crud:editar.html.twig',
							array(	'entity' 		=> $entity,
									'entidad'		=> $entidad,
									'edit_form'		=> $edit_form->createView(),
									'NecesitaImg'	=> $this->NecesitaImg(ucwords($entidad)),
									'creacionEdit'	=> $this->creacionEdit,
									'modificacionEdit'=> $this->modificacionEdit,
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
		$entidadNameSpaceEntidad 		= 'Gestor\CrudBundle\Entity\\'.ucwords($entidad);
		$entidadNameSpaceType 	= 'Gestor\CrudBundle\Form\\'.ucwords($entidad).'Type';
		
		$em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository($entidadNameSpace)->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('No se ha encontrado la entidad ['.$entidad.'] ');
        }

       
        $editForm = $this->createForm(new $entidadNameSpaceType(), $entity);
        $editForm->bind($request);	
		
		
		
		  if ($editForm->isValid()) {
			
			$this->BuscaMetodos($entity,'update');
			
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
           
			$entity_f = new Imagen;
			$mc = get_class_methods($entity_f);
			if($mc=='get'.ucwords($entidad)){
				$entity_foto = $em->getRepository('GestorCrudBundle:Imagen')->findByTodasLasFotos($id,$entidad);
				
				
				// Eliminamos las imagenes relacionadas
				
				$container =$this->container;
				foreach ($entity->getImagenes() as $nom){
					$nomruta = $nom->getSlug().'.'.$nom->getExtension();
					$entity_f->borrarArchivos($container,$nomruta); }
            }
           
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

	private function BuscaMetodos($entity,$estado)
	{
	
		$mc = get_class_methods($entity);
		foreach ($mc as $elmc) {
				
				if($estado=='create'){
					
					if($elmc=='setModificacion'){
						$entity->setModificacion(new \DateTime("now"));
						}
						
					if($elmc=='setCreacion'){
						$entity->setCreacion(new \DateTime("now"));
						}	
					
				} elseif ($estado=='update'){
					
					if($elmc=='setCreacion'){
						$entity->setCreacion($entity->getCreacion());
						}
					

					if($elmc=='setModificacion'){
						$entity->setModificacion(new \DateTime("now"));
						echo $entity->getModificacion()->format('Y-m-d H:i:s');
						}

					
				} elseif ($estado=='edit'){
				
					if($elmc=='setModificacion'){
						$this->modificacionEdit = ' Modificado: '.$entity->getModificacion()->format('d-m-Y H:i:s');
					}

					if($elmc=='setCreacion'){
						$this->creacionEdit = ' Creado: '.$entity->getCreacion()->format('d-m-Y H:i:s');
					}

					
				}	
				

					

				
			}	
		return 0;
	}	
}
