<?php

namespace ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ClientBundle\Entity\Reservation;
use ClientBundle\Form\Type\ReservationType;
use ClientBundle\Form\Type\ReservationFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Reservation controller.
 *
 */
class ReservationController extends Controller
{
    /**
     * Lists all Reservation entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm( ReservationFilterType::class);
        if (!is_null($response = $this->saveFilter($request,$form, 'reservation', 'reservation'))) {
            return $response;
        }
        $qb = $em->getRepository('ClientBundle:Reservation')->createQueryBuilder('r');
        $paginator = $this->filter($request,$form, $qb, 'reservation');
                return $this->render('ClientBundle:Reservation:index.html.twig', array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        ));
    }

    /**
     * Finds and displays a Reservation entity.
     *
     */
    public function showAction(Reservation $reservation)
    {
        $deleteForm = $this->createDeleteForm($reservation->getId(), 'reservation_delete');

        return $this->render('ClientBundle:Reservation:show.html.twig', array(
            'reservation' => $reservation,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Reservation entity.
     *
     */
    public function newAction()
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);

        return $this->render('ClientBundle:Reservation:new.html.twig', array(
            'reservation' => $reservation,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Reservation entity.
     *
     */
    public function createAction(Request $request)
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();

            return $this->redirect($this->generateUrl('reservation_show', array('id' => $reservation->getId())));
        }

        return $this->render('ClientBundle:Reservation:new.html.twig', array(
            'reservation' => $reservation,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Reservation entity.
     *
     */
    public function editAction(Reservation $reservation)
    {
        $editForm = $this->createForm( ReservationType::class, $reservation, array(
            'action' => $this->generateUrl('reservation_update', array('id' => $reservation->getId())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($reservation->getId(), 'reservation_delete');

        return $this->render('ClientBundle:Reservation:edit.html.twig', array(
            'reservation' => $reservation,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Reservation entity.
     *
     */
    public function updateAction(Reservation $reservation, Request $request)
    {
        $editForm = $this->createForm( ReservationType::class, $reservation, array(
            'action' => $this->generateUrl('reservation_update', array('id' => $reservation->getId())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('reservation_edit', array('id' => $reservation->getId())));
        }
        $deleteForm = $this->createDeleteForm($reservation->getId(), 'reservation_delete');

        return $this->render('ClientBundle:Reservation:edit.html.twig', array(
            'reservation' => $reservation,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Save order.
     *
     */
    public function sortAction(Request $request,$field, $type)
    {
        $this->setOrder($request,'reservation', $field, $type);

        return $this->redirect($this->generateUrl('reservation'));
    }

    /**
     * @param string $name  session name
     * @param string $field field name
     * @param string $type  sort type ("ASC"/"DESC")
     */
    protected function setOrder(Request $request,$name, $field, $type = 'ASC')
    {
        $request->getSession()->set('sort.' . $name, array('field' => $field, 'type' => $type));
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
        return $this->get('knp_paginator')->paginate($qb, $request->query->get('page', 1), 20);
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
     * Deletes a Reservation entity.
     *
     */
    public function deleteAction(Reservation $reservation, Request $request)
    {
        $form = $this->createDeleteForm($reservation->getId(), 'reservation_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reservation);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('reservation'));
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
