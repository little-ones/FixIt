<?php

namespace FixitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use FixitBundle\Entity\Reclamation;
use FixitBundle\Form\Type\ReclamationType;
use FixitBundle\Form\Type\ReclamationFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Reclamation controller.
 *
 */
class ReclamationController extends Controller
{
    /**
     * Lists all Reclamation entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm( ReclamationFilterType::class);
        if (!is_null($response = $this->saveFilter($request,$form, 'reclamation', 'reclamation'))) {
            return $response;
        }
        $qb = $em->getRepository('FixitBundle:Reclamation')->createQueryBuilder('r');
        $paginator = $this->filter($request,$form, $qb, 'reclamation');
                return $this->render('FixitBundle:Reclamation:index.html.twig', array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        ));
    }

    /**
     * Finds and displays a Reclamation entity.
     *
     */
    public function showAction(Reclamation $reclamation)
    {
        $deleteForm = $this->createDeleteForm($reclamation->getId(), 'reclamation_delete');

        return $this->render('FixitBundle:Reclamation:show.html.twig', array(
            'reclamation' => $reclamation,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Reclamation entity.
     *
     */
    public function newAction()
    {
        $reclamation = new Reclamation();
        $form = $this->createForm( ReclamationType::class, $reclamation);

        return $this->render('FixitBundle:Reclamation:new.html.twig', array(
            'reclamation' => $reclamation,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Reclamation entity.
     *
     */
    public function createAction(Request $request)
    {
        $reclamation = new Reclamation();
        $form = $this->createForm( ReclamationType::class, $reclamation);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reclamation);
            $em->flush();

            return $this->redirect($this->generateUrl('reclamation_show', array('id' => $reclamation->getId())));
        }

        return $this->render('FixitBundle:Reclamation:new.html.twig', array(
            'reclamation' => $reclamation,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Reclamation entity.
     *
     */
    public function editAction(Reclamation $reclamation)
    {
        $editForm = $this->createForm( ReclamationType::class, $reclamation, array(
            'action' => $this->generateUrl('reclamation_update', array('id' => $reclamation->getId())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($reclamation->getId(), 'reclamation_delete');

        return $this->render('FixitBundle:Reclamation:edit.html.twig', array(
            'reclamation' => $reclamation,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Reclamation entity.
     *
     */
    public function updateAction(Reclamation $reclamation, Request $request)
    {
        $editForm = $this->createForm( ReclamationType::class, $reclamation, array(
            'action' => $this->generateUrl('reclamation_update', array('id' => $reclamation->getId())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('reclamation_edit', array('id' => $reclamation->getId())));
        }
        $deleteForm = $this->createDeleteForm($reclamation->getId(), 'reclamation_delete');

        return $this->render('FixitBundle:Reclamation:edit.html.twig', array(
            'reclamation' => $reclamation,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Save order.
     *
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('reclamation', $field, $type);

        return $this->redirect($this->generateUrl('reclamation'));
    }

    /**
     * @param string $name  session name
     * @param string $field field name
     * @param string $type  sort type ("ASC"/"DESC")
     */
    protected function setOrder($name, $field, $type = 'ASC')
    {
        $this->getRequest()->getSession()->set('sort.' . $name, array('field' => $field, 'type' => $type));
    }

    /**
     * @param  string $name
     * @return array
     */
    protected function getOrder(Request $request,$name)
    {
        $session = $request->getSession();

        return $session->has('sort.' . $name) ? $session->get('sort.' . $name) : null;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $name
     */
    protected function addQueryBuilderSort(Request $request,QueryBuilder $qb, $name)
    {
        $alias = current($qb->getDQLPart('from'))->getAlias();
        if (is_array($order = $this->getOrder($request,$name))) {
            $qb->orderBy($alias . '.' . $order['field'], $order['type']);
        }
    }

    /**
     * Save filters
     *
     * @param  FormInterface $form
     * @param  string        $name   route/entity name
     * @param  string        $route  route name, if different from entity name
     * @param  array         $params possible route parameters
     * @return Response
     */
    protected function saveFilter(Request $request,FormInterface $form, $name, $route = null, array $params = null)
    {

        $url = $this->generateUrl($route ?: $name, is_null($params) ? array() : $params);
        if ($request->query->has('submit-filter') && $form->handleRequest($request)->isValid()) {
            $request->getSession()->set('filter.' . $name, $request->query->get($form->getName()));

            return $this->redirect($url);
        } elseif ($request->query->has('reset-filter')) {
            $request->getSession()->set('filter.' . $name, null);

            return $this->redirect($url);
        }
    }

    /**
     * Filter form
     *
     * @param  FormInterface                                       $form
     * @param  QueryBuilder                                        $qb
     * @param  string                                              $name
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    protected function filter(Request $request,FormInterface $form, QueryBuilder $qb, $name)
    {
        if (!is_null($values = $this->getFilter($request,$name))) {
            if ($form->submit($values)->isValid()) {
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $qb);
            }
        }

        // possible sorting
        $this->addQueryBuilderSort($request,$qb, $name);
        return $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 5);
    }

    /**
     * Get filters from session
     *
     * @param  string $name
     * @return array
     */
    protected function getFilter(Request $request,$name)
    {
        return $request->getSession()->get('filter.' . $name);
    }

    /**
     * Deletes a Reclamation entity.
     *
     */
    public function deleteAction(Reclamation $reclamation, Request $request)
    {
        $form = $this->createDeleteForm($reclamation->getId(), 'reclamation_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reclamation);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('reclamation'));
    }

    /**
     * Create Delete form
     *
     * @param integer                       $id
     * @param string                        $route
     * @return \Symfony\Component\Form\Form
     */
    protected function createDeleteForm($id, $route)
    {
        return $this->createFormBuilder(null, array('attr' => array('id' => 'delete')))
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

}
