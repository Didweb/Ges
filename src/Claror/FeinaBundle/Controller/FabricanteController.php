<?php

namespace Claror\FeinaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Claror\FeinaBundle\Entity\Fabricante;
use Claror\FeinaBundle\Form\FabricanteType;

/**
 * Fabricante controller.
 *
 * @Route("/gestor/fabricante")
 */
class FabricanteController extends Controller
{

    /**
     * Lists all Fabricante entities.
     *
     * @Route("/", name="gestor_fabricante")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
		$dql = "SELECT f FROM ClarorFeinaBundle:Fabricante f";
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
     * Creates a new Fabricante entity.
     *
     * @Route("/", name="gestor_fabricante_create")
     * @Method("POST")
     * @Template("ClarorFeinaBundle:Fabricante:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Fabricante();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
			$this->get('session')->getFlashBag()
						->add('fabricante_ok',
						's\'ha creat un nou  fabricant.');
            return $this->redirect($this->generateUrl('gestor_fabricante_edit', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Fabricante entity.
    *
    * @param Fabricante $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Fabricante $entity)
    {
        $form = $this->createForm(new FabricanteType(), $entity, array(
            'action' => $this->generateUrl('gestor_fabricante_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear nou fabricant'));

        return $form;
    }

    /**
     * Displays a form to create a new Fabricante entity.
     *
     * @Route("/new", name="gestor_fabricante_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Fabricante();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Fabricante entity.
     *
     * @Route("/{id}", name="gestor_fabricante_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClarorFeinaBundle:Fabricante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fabricante entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Fabricante entity.
     *
     * @Route("/{id}/edit", name="gestor_fabricante_edit")
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

        $entity = $em->getRepository('ClarorFeinaBundle:Fabricante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fabricante entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

		$dql = "SELECT COUNT(f.id) as total FROM ClarorFeinaBundle:Faena f WHERE f.fabricante=:idcat ";
		$query = $em->createQuery($dql)->setParameter('idcat',$id);
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
    * Creates a form to edit a Fabricante entity.
    *
    * @param Fabricante $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Fabricante $entity)
    {
        $form = $this->createForm(new FabricanteType(), $entity, array(
            'action' => $this->generateUrl('gestor_fabricante_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualitzar fabricant'));

        return $form;
    }
    /**
     * Edits an existing Fabricante entity.
     *
     * @Route("/{id}", name="gestor_fabricante_update")
     * @Method("PUT")
     * @Template("ClarorFeinaBundle:Fabricante:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClarorFeinaBundle:Fabricante')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fabricante entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
			$this->get('session')->getFlashBag()
						->add('fabricante_ok',
						's\'ha actualitzat el fabricant de forma correcta.');
            return $this->redirect($this->generateUrl('gestor_fabricante_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Fabricante entity.
     *
     * @Route("/{id}", name="gestor_fabricante_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ClarorFeinaBundle:Fabricante')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Fabricante entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('gestor_fabricante'));
    }

    /**
     * Creates a form to delete a Fabricante entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gestor_fabricante_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar fabricant'))
            ->getForm()
        ;
    }
}
