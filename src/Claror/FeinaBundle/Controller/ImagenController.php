<?php

namespace Claror\FeinaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Claror\FeinaBundle\Entity\Imagen;
use Claror\FeinaBundle\Form\ImagenType;
use Claror\FeinaBundle\Form\ImagenShowType;


/**
 * Imagen controller.
 *
 * @Route("/gestor/imagen")
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

        $entities = $em->getRepository('ClarorFeinaBundle:Imagen')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Imagen entity.
     *
     * @Route("/", name="gestor_imagen_create")
     * @Method("POST")
     * @Template("ClarorFeinaBundle:Imagen:new.html.twig")
     */
    public function createAction(Request $request)
    {
		 
		
        $entity = new Imagen();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
			
			
			$tipoarchivo=$entity->getFile()->getMimeType();
			
			if($tipoarchivo<>'image/jpeg' &&  $tipoarchivo<>'image/png')
			{
			$this->get('session')->getFlashBag()
					->add('faena_error',
					'Format d\'arxiu no permès. Formats permesos:Jpg/Jpeg o Png.');	
			return $this->redirect($this->generateUrl('gestor_faena_edit', array('id' => $entity->getFaena()->getId())));	
			}
			
			
            $em = $this->getDoctrine()->getManager();
            
            
            
            
            $entity->setExtension();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()
					->add('faena_ok',
					'Imatge pujada al servidor de forma correcta.');	
			$elnombre = $entity->getSlug().'.'.$entity->getExtension();
          
           $entity->upload(100,100,$elnombre);

            return $this->redirect($this->generateUrl('gestor_faena_edit', array('id' => $entity->getFaena()->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Imagen entity.
    *
    * @param Imagen $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Imagen $entity)
    {
        $form = $this->createForm(new ImagenType(), $entity, array(
            'action' => $this->generateUrl('gestor_imagen_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Pujar imatge'));

        return $form;
    }

    /**
     * Displays a form to create a new Imagen entity.
     *
     * @Route("/new", name="gestor_imagen_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($idfaena)
    {
		 
		$em = $this->getDoctrine()->getManager();
       
		
        $entity = new Imagen();
        $idfa= $em->getReference('ClarorFeinaBundle:Faena', $idfaena);
        $entity->setFaena($idfa);
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Imagen entity.
     *
     * @Route("/{id}", name="gestor_imagen_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($idfaena)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('ClarorFeinaBundle:Imagen')->findByFaena($idfaena,array('orden' => 'ASC'));
        
		$texto = '';
        if (!$entity) {
            $texto = 'Aquesta feina no té cap imatge assignada.';
        }
		$losf = array();
		for ($i=0;$i<=count($entity)-1;$i++){
			 $editForm = $this->createEditForm($entity[$i])
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
            'entity'    => $entity,
            'texto'		=> $texto,
            'edit_form_img'   => $losf,
            'imagen'	=> $imagen
        );
    }

    /**
     * Displays a form to edit an existing Imagen entity.
     *
     * @Route("/{id}/edit", name="gestor_imagen_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClarorFeinaBundle:Imagen')->find($id);

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
    private function createEditForm(Imagen $entity)
    {
        $form = $this->createForm(new ImagenShowType(), $entity, array(
            'action' => $this->generateUrl('gestor_imagen_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualitzar'));

        return $form;
    }
    /**
     * Edits an existing Imagen entity.
     *
     * @Route("/{id}", name="gestor_imagen_update")
     * @Method("PUT")
     * @Template("ClarorFeinaBundle:Imagen:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClarorFeinaBundle:Imagen')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Imagen entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('gestor_faena_edit', array('id' => $entity->getFaena()->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }
    /**
     * Deletes a Imagen entity.
     *
     * @Route("/elimina/{id}/{idfaena}", name="gestor_imagen_delete")
     */
    public function deleteAction(Request $request, $id,$idfaena)
    {
        
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ClarorFeinaBundle:Imagen')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Imagen entity.');
            }
			$nomruta = $entity->getSlug().'.'.$entity->getExtension();
			
		$entity->borrarArchivos($nomruta);
            $em->remove($entity);
            $em->flush();
            
        

        return $this->redirect($this->generateUrl('gestor_faena_edit', array('id' => $idfaena)));
    }



}
