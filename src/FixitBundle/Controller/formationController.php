<?php

namespace FixitBundle\Controller;

use FixitBundle\Entity\formation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Formation controller.
 *
 */
class formationController extends Controller
{
    /**
     * Lists all formation entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $formations = $em->getRepository('FixitBundle:formation')->findAll();

        return $this->render('@Fixit/formation/index.html.twig', array(
            'formations' => $formations,
        ));
    }
    public function index1Action()
    {
        $em = $this->getDoctrine()->getManager();

        $formations = $em->getRepository('FixitBundle:formation')->findAll();

        return $this->render('@Fixit/formation/index1.html.twig', array(
            'formations' => $formations,
        ));
    }


    /**
     * Creates a new formation entity.
     *
     */
    public function newAction(Request $request)
    {
        $formation = new Formation();
        $form = $this->createForm('FixitBundle\Form\formationType', $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();

            return $this->redirectToRoute('formation_show', array('id' => $formation->getId()));
        }

        return $this->render('@Fixit/formation/new.html.twig', array(
            'formation' => $formation,
            'form' => $form->createView(),
        ));
    }
    public function new1Action(Request $request)
    {
        $formation = new Formation();
        $form = $this->createForm('FixitBundle\Form\formationType', $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($formation);
            $em->flush();

            return $this->redirectToRoute('formation_show2', array('id' => $formation->getId()));
        }

        return $this->render('@Fixit/formation/new1.html.twig', array(
            'formation' => $formation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a formation entity.
     *
     */
    public function showAction(formation $formation)
    {
        $deleteForm = $this->createDeleteForm($formation);

        return $this->render('@Fixit/formation/show.html.twig', array(
            'formation' => $formation,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    public function show1Action(formation $formation)
    {
        $deleteForm = $this->createDeleteForm($formation);

        return $this->render('@Fixit/formation/show1.html.twig', array(
            'formation' => $formation,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    public function show2Action(formation $formation)
    {
        $deleteForm = $this->createDeleteForm($formation);

        return $this->render('@Fixit/formation/show2.html.twig', array(
            'formation' => $formation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing formation entity.
     *
     */
    public function editAction(Request $request, formation $formation)
    {
        $deleteForm = $this->createDeleteForm($formation);
        $editForm = $this->createForm('FixitBundle\Form\formationType', $formation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('formation_edit', array('id' => $formation->getId()));
        }

        return $this->render('@Fixit/formation/edit.html.twig', array(
            'formation' => $formation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    public function edit1Action(Request $request, formation $formation)
    {
        $deleteForm = $this->createDeleteForm($formation);
        $editForm = $this->createForm('FixitBundle\Form\formationType', $formation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('formation_edit', array('id' => $formation->getId()));
        }

        return $this->render('@Fixit/formation/edit1.html.twig', array(
            'formation' => $formation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a formation entity.
     *
     */
    public function deleteAction(Request $request, formation $formation)
    {
        $form = $this->createDeleteForm($formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($formation);
            $em->flush();
        }

        return $this->redirectToRoute('formation_index');
    }
    public function delete1Action(Request $request, formation $formation)
    {
        $form = $this->createDeleteForm($formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($formation);
            $em->flush();
        }

        return $this->redirectToRoute('formation_index1');
    }

    /**
     * Creates a form to delete a formation entity.
     *
     * @param formation $formation The formation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(formation $formation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('formation_delete1', array('id' => $formation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
