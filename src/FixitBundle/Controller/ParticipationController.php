<?php

namespace FixitBundle\Controller;

use FixitBundle\Entity\evenement;
use FixitBundle\Entity\Participation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Participation controller.
 *
 */
class ParticipationController extends Controller
{
    /**
     * Lists all participation entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $participations = $em->getRepository('FixitBundle:Participation')->findAll();

        return $this->render('@Fixit/participation/index.html.twig', array(
            'participations' => $participations,
        ));
    }
    public function index1Action()
    {
        $em = $this->getDoctrine()->getManager();

        $participations = $em->getRepository('FixitBundle:Participation')->findAll();

        return $this->render('@Fixit/participation/index1.html.twig', array(
            'participations' => $participations,
        ));
    }

    /**
     * Creates a new participation entity.
     *
     */
    public function newAction(Request $request)
    {
        $participation = new Participation();
        $form = $this->createForm('FixitBundle\Form\ParticipationType', $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participation);
            $em->flush();

            return $this->redirectToRoute('participation_show', array('id' => $participation->getId()));
        }

        return $this->render('@Fixit/participation/new.html.twig', array(
            'participation' => $participation,
            'form' => $form->createView(),
        ));
    }
    public function new1Action(Request $request)
    {
        $participation = new Participation();
        $form = $this->createForm('FixitBundle\Form\ParticipationType', $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($participation);
            $em->flush();

            return $this->redirectToRoute('participation_show1', array('id' => $participation->getId()));
        }

        return $this->render('@Fixit/participation/new1.html.twig', array(
            'participation' => $participation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a participation entity.
     *
     */
    public function showAction(Participation $participation)
{
    $deleteForm = $this->createDeleteForm($participation);

    return $this->render('@Fixit/participation/show.html.twig', array(
        'participation' => $participation,
        'delete_form' => $deleteForm->createView(),
    ));
}
    public function show1Action(Participation $participation)
    {
        $deleteForm = $this->createDeleteForm($participation);

        return $this->render('@Fixit/participation/show1.html.twig', array(
            'participation' => $participation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing participation entity.
     *
     */
    public function editAction(Request $request, Participation $participation)
    {
        $deleteForm = $this->createDeleteForm($participation);
        $editForm = $this->createForm('FixitBundle\Form\ParticipationType', $participation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('participation_edit', array('id' => $participation->getId()));
        }

        return $this->render('@Fixit/participation/edit.html.twig', array(
            'participation' => $participation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    public function edit1Action(Request $request, Participation $participation)
    {
        $deleteForm = $this->createDeleteForm($participation);
        $editForm = $this->createForm('FixitBundle\Form\ParticipationType', $participation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('participation_edit1', array('id' => $participation->getId()));
        }

        return $this->render('@Fixit/participation/edit1.html.twig', array(
            'participation' => $participation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a participation entity.
     *
     */
    public function deleteAction(Request $request, Participation $participation)
    {
        $form = $this->createDeleteForm($participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($participation);
            $em->flush();
        }

        return $this->redirectToRoute('participation_index');
    }
    public function delete1Action(Request $request, Participation $participation)
    {
        $form = $this->createDeleteForm($participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($participation);
            $em->flush();
        }

        return $this->redirectToRoute('participation_index1');
    }

    /**
     * Creates a form to delete a participation entity.
     *
     * @param Participation $participation The participation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Participation $participation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('participation_delete', array('id' => $participation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

public function ParticiperAction(Request $request, $id)
{
    $m = $this->getDoctrine()->getManager();
    $evemenet = $m->getRepository(evenement::class)->find($id);
    if ($evemenet->getNbreParticipant() > 0) {
        $Participation = new Participation();
        $Participation->setuser($this->getUser());
        $Participation->setevenement($evemenet);
        $evemenet->setNbreParticipant($evemenet->getNbreParticipant()-1);
        $m->persist($Participation);
        $m->flush();
        $m->persist($evemenet);
        $m->flush();
        return $this->redirectToRoute('evenement_index1');
    } else {
        $this->addFlash('success','Vous ne pouvez pas participer à votre évennement !!');
        return $this->redirectToRoute('evenement_index1');
    }
}
}
