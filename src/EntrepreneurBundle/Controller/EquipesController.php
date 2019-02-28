<?php

namespace EntrepreneurBundle\Controller;

use EntrepreneurBundle\Entity\Equipes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Equipe controller.
 *
 */
class EquipesController extends Controller
{
    /**
     * Lists all equipe entities.
     *
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $user->getId();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('EntrepreneurBundle:Equipes')->createQueryBuilder('d')->where('d.proprietere=:id')
            ->setParameter('id',$user)->getQuery();
        $equipes = $qb->getResult();


        return $this->render('@Entrepreneur/equipes/index.html.twig', array(
            'equipes' => $equipes,
        ));
    }

    /**
     * Creates a new equipe entity.
     *
     */
    public function newAction(Request $request)
    {
        $equipe = new Equipes();
        $form = $this->createForm('EntrepreneurBundle\Form\EquipesType', $equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted()    ) {
            $equipe->setProprietere($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($equipe);
            $em->flush();

            return $this->redirectToRoute('equipes_show', array('id' => $equipe->getId()));
        }

        return $this->render('@Entrepreneur/equipes/new.html.twig', array(
            'equipe' => $equipe,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a equipe entity.
     *
     */
    public function showAction(Equipes $equipe)
    {
        $deleteForm = $this->createDeleteForm($equipe);

        return $this->render('@Entrepreneur/equipes/show.html.twig', array(
            'equipe' => $equipe,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing equipe entity.
     *
     */
    public function editAction(Request $request, Equipes $equipe)
    {
        $deleteForm = $this->createDeleteForm($equipe);
        $editForm = $this->createForm('EntrepreneurBundle\Form\EquipesType', $equipe);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() ) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('equipes_index');
        }

        return $this->render('@Entrepreneur/equipes/edit.html.twig', array(
            'equipe' => $equipe,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a equipe entity.
     *
     */
    public function deleteAction(Request $request, Equipes $equipe)
    {
        $form = $this->createDeleteForm($equipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($equipe);
            $em->flush();
        }

        return $this->redirectToRoute('equipes_index');
    }

    /**
     * Creates a form to delete a equipe entity.
     *
     * @param Equipes $equipe The equipe entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Equipes $equipe)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('equipes_delete', array('id' => $equipe->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
