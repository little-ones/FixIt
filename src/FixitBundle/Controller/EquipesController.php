<?php

namespace FixitBundle\Controller;

use FixitBundle\Entity\Equipes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FixitBundle\Form\EquipesType;

/**
 * Equipe controller.
 *
 * @Route("equipes")
 */
class EquipesController extends Controller
{
    /**
     * Lists all equipe entities.
     *
     * @Route("/", name="equipes_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $equipes = $em->getRepository('FixitBundle:Equipes')->findAll();

        return $this->render('@Fixit/equipes/equipes.html.twig', array(
            'equipes' => $equipes,
        ));
    }

    /**
     * Creates a new equipe entity.
     *
     * @Route("/new", name="equipes_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */


    public function new_equipesAction(Request $request)
    {
        $equipe = new Equipes();
        $form = $this->createForm(EquipesType::class, $equipe);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($equipe);
            $em->flush();

            return $this->redirectToRoute('equipes_index');

        }
        return $this->render('@Fixit/equipes/equipes_new.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * Finds and displays a equipe entity.
     *
     * @Route("/{id}", name="equipes_show")
     * @Method("GET")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $equipe = $this->getDoctrine()->getManager()->getRepository(Equipes::class)->find($id);
        $deleteForm = $this->createDeleteForm($equipe);

        return $this->render('@Fixit/equipes/equipes_show.html.twig', array(
            'equipe' => $equipe,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing equipe entity.
     *
     * @Route("/{id}/edit", name="equipes_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */


    public function edit_equipesAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $offre = $em->getRepository(Equipes::class)->find($id);

        $form = $this->createForm(EquipesType::class, $offre);
        $form->setData($offre);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($offre);
            $em->flush();
            return $this->redirectToRoute('fixit_equipes');
        }
        return $this->render('@Fixit/equipes/equipes_edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * Deletes a equipe entity.
     *
     * @Route("/{id}", name="equipes_delete")
     * @Method("DELETE")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $equipe = $this->getDoctrine()->getManager()->getRepository(Equipes::class)->find($id);
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($equipe);
            $em->flush();
        }

        return $this->redirectToRoute('fixit_equipes');
    }

    /**
     * Creates a form to delete a equipe entity.
     *
     * @param $id
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm($id)
    {
        $equipe = $this->getDoctrine()->getManager()->getRepository(Equipes::class)->find($id);
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fixit_equipes_delete', array('id' => $equipe->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
