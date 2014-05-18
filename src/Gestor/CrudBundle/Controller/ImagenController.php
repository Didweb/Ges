<?php

namespace Gestor\CrudBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gestor\CrudBundle\Entity\Imagen;
use Gestor\CrudBundle\Form\ImagenType;
use Gestor\CrudBundle\Form\ImagenShowType;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
/**
 * Imagen controller.
 *
 */
class ImagenController extends Controller
{

    /**
     * Lists all Imagen entities.
     *
     * @Route("/", name="gestor_imagen")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('GestorCrudBundle:Imagen')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Imagen entity.
     *
     * 
     * @Method("POST")
     * @Template("GestorCrudBundle:Imagen:new.html.twig")
     */
    public function createAction(Request $request,$entidad)
    {
		$container =$this->container;
		$entityGet = 'get'.ucwords($entidad);
        $entity = new Imagen();
        
        $form = $this->formImg($entidad,$entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
			
		
			$entity->setExtension();
			$nb=$entity->getExtension();
			
			if($nb<>'jpeg' &&  $nb<>'png' &&  $nb<>'jpg')
			{
			$this->get('session')->getFlashBag()
					->add('error_foto',
					'Formato de archivo [ <b>'.$nb.'</b> ] no permitido.');	
			return $this->redirect($this->generateUrl('gestor_editar_registro', array(
													'id' 		=> $entity->$entityGet()->getId(),
													'entidad' 	=> $entidad )));	
			}
			
			
            $em = $this->getDoctrine()->getManager();
            
            
            
            
            $entity->setExtension();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()
					->add('faena_ok',
					'Imatge pujada al servidor de forma correcta.');	
			$elnombre = $entity->getSlug().'.'.$entity->getExtension();
			
          
          
          	$resize = $this->get('didweb_resize.acciones');
            $resize->upload($elnombre,$entity->getFile());

            return $this->redirect($this->generateUrl('gestor_editar_registro', array(
											'id' 		=> $entity->$entityGet()->getId(),
											'entidad' 	=> $entidad
											)));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }



    /**
     * Displays a form to create a new Imagen entity.
     *
     *
     * @Method("GET")
     * @Template()
     */
    public function newAction($identidad,$entidad)
    {	
		$entidadNameSpace 		= 'GestorCrudBundle:'.ucwords($entidad);
		$entidadNameSpaceType 	= 'Gestor\CrudBundle\Form\\'.ucwords($entidad).'Type';
		$entidadSet				= 'set'.ucwords($entidad); 
		 
		$em = $this->getDoctrine()->getManager();
       
		$entidadNameSpace 		= 'GestorCrudBundle:'.ucwords($entidad);
		$entidadNameSpaceType 	= 'Gestor\CrudBundle\Form\\'.ucwords($entidad).'Type';
		$entidadSet				= 'set'.ucwords($entidad); 
		
		$entity = new Imagen();
        $idtot= $em->getReference($entidadNameSpace, $identidad);
        $entity->$entidadSet($idtot);
		
        $form = $this->formImg($entidad,$entity);


        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'entidad'=> $entidad
        );
    }


	public function formImg($entidad,$entity)
	{
		$entidadNameSpace 		= 'GestorCrudBundle:'.ucwords($entidad);
		$entidadNameSpaceType 	= 'Gestor\CrudBundle\Form\\'.ucwords($entidad).'Type';
		$entidadSet				='set'.ucwords($entidad); 
		
		$form = $this->createFormBuilder($entity)
			->add('nombre','text',array('required' => true))
            ->add('file','file',array('required' => true))
            ->add('orden')
            ->add('slug','hidden')
            ->add($entidad, 'entity_id', array(
            'class' => $entidadNameSpace,))
            ->getForm()
            ;	
		return $form;
	}

    /**
     * Finds and displays a Imagen entity.
     *
     * @Method("GET")
     * @Template()
     */
    public function showAction($identidad,$entidad)
    {
		$entidadFind 		= 'findBy'.ucwords($entidad);
		
        $em = $this->getDoctrine()->getManager();
        
         $entity = $em->getRepository('GestorCrudBundle:Imagen')->$entidadFind($identidad,array('orden' => 'ASC'));
        
		$texto = '';
        if (!$entity) {
            $texto = 'No existe ningúna imagen asociada';
        }
		$losf = array();
		for ($i=0;$i<=count($entity)-1;$i++){
			 $editForm = $this->createEditForm($entity[$i],$entidad,$identidad)
			->createView();
			
			$losf[$i]=$editForm;
			
			}
		$i=0;
		$imagen=array();	
        foreach($entity as $nom=>$val)
        {
        $imagen[$i]=$val->getSlug().'.'.$val->getExtension();
        
        $i++;
        }

        return array(
            'entity'    	=> $entity,
            'texto'			=> $texto,
            'edit_form_img' => $losf,
            'imagen'		=> $imagen,
            'identidad'		=> $identidad,
            'entidad'		=> $entidad
        );
    }

    /**
     * Displays a form to edit an existing Imagen entity.
     *
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GestorCrudBundle:Imagen')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Imagen entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Imagen entity.
    *
    * @param Imagen $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Imagen $entity,$entidad,$identidad)
    {
        $form = $this->createForm(new ImagenShowType(), $entity, array(
            'action' => $this->generateUrl('gestor_imagen_update', array(
								'idfoto' 	=> $entity->getId(),
								'identidad' => $identidad,
								'entidad'	=> $entidad
								)),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualitzar'));

        return $form;
    }
    /**
     * Edits an existing Imagen entity.
     *
     * 
     * @Method("PUT")
     */
    public function updateAction(Request $request, $idfoto,$entidad,$identidad)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GestorCrudBundle:Imagen')->find($idfoto);
		$nombreViejo = $entity->getSlug().'.'.$entity->getExtension();
		
        if (!$entity) {
            throw $this->createNotFoundException('No se ha encontrado la entidad Imagen [updateAction en ImagenController].');
        }

       
        $editForm = $this->createForm(new ImagenShowType(), $entity);
        $editForm->bind($request);
        
        $entity->setSlug($entity->getNombre());
		$nombreNuevo = $entity->getSlug().'.'.$entity->getExtension();
        
            $em->flush();

			$resize = $this->get('didweb_resize.acciones');
			$resize->CambioNombreImg($nombreViejo,$nombreNuevo);
			
		$this->get('session')->getFlashBag()->add('editar_ok', 'Imagen modificada.');	
			
        return $this->redirect($this->generateUrl('gestor_editar_registro', array('id' => $identidad,'entidad'=>$entidad)));

    }
    /**
     * Deletes a Imagen entity.
     *
     */
    public function deleteAction(Request $request, $idfoto,$id,$entidad)
    {
			$container = $this->container;
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GestorCrudBundle:Imagen')->find($idfoto);

            if (!$entity) {
                throw $this->createNotFoundException('No se ha encontrado la entidad Imagen en [deleteAction de imagenController].');
            }
			$nomruta = $entity->getSlug().'.'.$entity->getExtension();
			
			$resize = $this->get('didweb_resize.acciones');
			$resize->borrarArchivos($nomruta);
			
            $em->remove($entity);
            $em->flush();
            
        

        return $this->redirect($this->generateUrl('gestor_editar_registro', array('id' => $id,'entidad'=>$entidad) ) );
    }



}
