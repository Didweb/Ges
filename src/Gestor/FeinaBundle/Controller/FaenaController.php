<?php

namespace Gestor\FeinaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gestor\FeinaBundle\Entity\Faena;
use Gestor\FeinaBundle\Form\FaenaType;

/**
 * Faena controller.
 *
 * @Route("/gestor/faena")
 */
class FaenaController extends Controller
{


    /**
     * Lists all Faena entities.
     *
     * @Route("/", name="gestor_faena")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
		$dql = "SELECT f FROM GestorFeinaBundle:Faena f ";
		$query = $em->createQuery($dql);

		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
        $query,
        $this->get('request')->query->get('p', 1),
        25
		);		
       

        return array(
            'pagination' => $pagination,
        );
    }
    /**
     * Creates a new Faena entity.
     *
     * @Route("/", name="gestor_faena_create")
     * @Method("POST")
     * @Template("GestorFeinaBundle:Faena:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Faena();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
			$this->get('session')->getFlashBag()
						->add('faena_ok',
						's\'ha creat una nova  feina.');
            return $this->redirect($this->generateUrl('gestor_faena_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Faena entity.
    *
    * @param Faena $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Faena $entity)
    {
        $form = $this->createForm(new FaenaType(), $entity, array(
            'action' => $this->generateUrl('gestor_faena_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear nova feina'));

        return $form;
    }

    /**
     * Displays a form to create a new Faena entity.
     *
     * @Route("/new", name="gestor_faena_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Faena();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Faena entity.
     *
     * @Route("/{id}", name="gestor_faena_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GestorFeinaBundle:Faena')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Faena entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Faena entity.
     *
     * @Route("/{id}/edit", name="gestor_faena_edit")
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

        $entity = $em->getRepository('GestorFeinaBundle:Faena')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Faena entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'cam'		  => $cam
        );
    }

    /**
    * Creates a form to edit a Faena entity.
    *
    * @param Faena $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Faena $entity)
    {
        $form = $this->createForm(new FaenaType(), $entity, array(
            'action' => $this->generateUrl('gestor_faena_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualitzar Feina'));

        return $form;
    }
    /**
     * Edits an existing Faena entity.
     *
     * @Route("/{id}", name="gestor_faena_update")
     * @Method("PUT")
     * @Template("GestorFeinaBundle:Faena:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('GestorFeinaBundle:Faena')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Faena entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
			$this->get('session')->getFlashBag()
						->add('faena_ok',
						's\'ha actualitzat la feina de forma correcta.');
            return $this->redirect($this->generateUrl('gestor_faena_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Faena entity.
     *
     * @Route("/{id}", name="gestor_faena_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('GestorFeinaBundle:Faena')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Faena entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('gestor_faena'));
    }

    /**
     * Creates a form to delete a Faena entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gestor_faena_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
