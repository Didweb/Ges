<?php

namespace Claror\FeinaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Claror\FeinaBundle\Entity\Categoria;
use Claror\FeinaBundle\Form\CategoriaType;



/**
 * Categoria controller.
 *
 * @Route("/gestor/categoria")
 */
class CategoriaController extends Controller
{

    /**
     * Lists all Categoria entities.
     *
     * @Route("/", name="gestor_categoria")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
		$dql = "SELECT c FROM ClarorFeinaBundle:Categoria c";
		$query = $em->createQuery($dql);
        
        $paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
        $query,
        $this->get('request')->query->get('p', 1),
        25
		);
        
        return array(
            'pagination' => $pagination
        );
    }
    /**
     * Creates a new Categoria entity.
     *
     * @Route("/", name="gestor_categoria_create")
     * @Method("POST")
     * @Template("ClarorFeinaBundle:Categoria:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Categoria();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
			$this->get('session')->getFlashBag()
						->add('categoria_ok',
						's\'ha creat una nova categoria.');
            return $this->redirect($this->generateUrl('gestor_categoria_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Categoria entity.
    *
    * @param Categoria $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Categoria $entity)
    {
        $form = $this->createForm(new CategoriaType(), $entity, array(
            'action' => $this->generateUrl('gestor_categoria_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear nova categoria'));

        return $form;
    }

    /**
     * Displays a form to create a new Categoria entity.
     *
     * @Route("/new", name="gestor_categoria_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Categoria();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Categoria entity.
     *
     * @Route("/{id}", name="gestor_categoria_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClarorFeinaBundle:Categoria')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Categoria entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Categoria entity.
     *
     * @Route("/{id}/edit", name="gestor_categoria_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Request $request,$id,$locale='')
    {
		if($locale=='es'){
			$cam='ca';
			}elseif($locale=='ca'){
				$cam='es';
				}elseif ($locale=='')
				{
				$cam=$request->getSession()->get('_locale');	
				}
		$request->getSession()->set('_locale', $cam);
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClarorFeinaBundle:Categoria')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Categoria entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

	
		
		$query = $em->getRepository('ClarorFeinaBundle:Faena')
					->findByTieneCategoria($id);
		
		$perbor = $query->getResult();
		
		
		
        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'cam'		  => $cam,
            'borrar_perm' => $perbor[0]['total']
            
        );
    }

    /**
    * Creates a form to edit a Categoria entity.
    *
    * @param Categoria $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Categoria $entity)
    {
        $form = $this->createForm(new CategoriaType(), $entity, array(
            'action' => $this->generateUrl('gestor_categoria_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualitzar categoria'));

        return $form;
    }
    /**
     * Edits an existing Categoria entity.
     *
     * @Route("/{id}", name="gestor_categoria_update")
     * @Method("PUT")
     * @Template("ClarorFeinaBundle:Categoria:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClarorFeinaBundle:Categoria')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Categoria entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
			$this->get('session')->getFlashBag()
						->add('categoria_ok',
						's\'ha actualitzat la categoria de forma correcta.');
            return $this->redirect($this->generateUrl('gestor_categoria_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Categoria entity.
     *
     * @Route("/{id}", name="gestor_categoria_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ClarorFeinaBundle:Categoria')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Categoria entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('gestor_categoria'));
    }

    /**
     * Creates a form to delete a Categoria entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gestor_categoria_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar categoria'))
            ->getForm()
        ;
    }
}
