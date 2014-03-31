<?php

namespace Claror\FeinesBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Claror\FeinesBundle\Entity\Feina;
use Claror\FeinesBundle\Form\FeinaType;

/**
 * Feina controller.
 *
 * @Route("/control/feina")
 */
class FeinaController extends Controller
{

    /**
     * Lists all Feina entities.
     *
     * @Route("/", name="control_feina")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('ClarorFeinesBundle:Feina')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Feina entity.
     *
     * @Route("/", name="control_feina_create")
     * @Method("POST")
     * @Template("ClarorFeinesBundle:Feina:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Feina();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('control_feina_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Feina entity.
    *
    * @param Feina $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Feina $entity)
    {
        $form = $this->createForm(new FeinaType(), $entity, array(
            'action' => $this->generateUrl('control_feina_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Feina entity.
     *
     * @Route("/new", name="control_feina_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Feina();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Feina entity.
     *
     * @Route("/{id}", name="control_feina_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClarorFeinesBundle:Feina')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feina entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Feina entity.
     *
     * @Route("/{id}/edit", name="control_feina_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClarorFeinesBundle:Feina')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feina entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Feina entity.
    *
    * @param Feina $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Feina $entity)
    {
        $form = $this->createForm(new FeinaType(), $entity, array(
            'action' => $this->generateUrl('control_feina_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Feina entity.
     *
     * @Route("/{id}", name="control_feina_update")
     * @Method("PUT")
     * @Template("ClarorFeinesBundle:Feina:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('ClarorFeinesBundle:Feina')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feina entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('control_feina_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Feina entity.
     *
     * @Route("/{id}", name="control_feina_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('ClarorFeinesBundle:Feina')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Feina entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('control_feina'));
    }

    /**
     * Creates a form to delete a Feina entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('control_feina_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
