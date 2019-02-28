<?php

namespace ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ClientBundle\Entity\Service;
use ClientBundle\Form\Type\ServiceType;
use ClientBundle\Form\Type\ServiceFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Service controller.
 *
 */
class ServiceController extends Controller
{
    /**
     * Lists all Service entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm( ServiceFilterType::class);
        if (!is_null($response = $this->saveFilter($request,$form, 'service', 'service'))) {
            return $response;
        }
        $qb = $em->getRepository('ClientBundle:Service')->createQueryBuilder('s');
        $paginator = $this->filter($request,$form, $qb, 'service');
                return $this->render('ClientBundle:Service:index.html.twig', array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        ));
    }

    /**
     * Finds and displays a Service entity.
     *
     */
    public function showAction(Service $service)
    {
        $deleteForm = $this->createDeleteForm($service->getId(), 'service_delete');

        return $this->render('ClientBundle:Service:show.html.twig', array(
            'service' => $service,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Service entity.
     *
     */
    public function newAction()
    {
        $service = new Service();
        $form = $this->createForm( ServiceType::class, $service);

        return $this->render('ClientBundle:Service:new.html.twig', array(
            'service' => $service,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Service entity.
     *
     */
    public function createAction(Request $request)
    {
        $service = new Service();
        $form = $this->createForm( ServiceType::class, $service);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();

            return $this->redirect($this->generateUrl('service_show', array('id' => $service->getId())));
        }

        return $this->render('ClientBundle:Service:new.html.twig', array(
            'service' => $service,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Service entity.
     *
     */
    public function editAction(Service $service)
    {
        $editForm = $this->createForm( ServiceType::class, $service, array(
            'action' => $this->generateUrl('service_update', array('id' => $service->getId())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($service->getId(), 'service_delete');

        return $this->render('ClientBundle:Service:edit.html.twig', array(
            'service' => $service,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Service entity.
     *
     */
    public function updateAction(Service $service, Request $request)
    {
        $editForm = $this->createForm( ServiceType::class, $service, array(
            'action' => $this->generateUrl('service_update', array('id' => $service->getId())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('service_edit', array('id' => $service->getId())));
        }
        $deleteForm = $this->createDeleteForm($service->getId(), 'service_delete');

        return $this->render('ClientBundle:Service:edit.html.twig', array(
            'service' => $service,
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
        $this->setOrder($request,'service', $field, $type);

        return $this->redirect($this->generateUrl('service'));
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
     * Deletes a Service entity.
     *
     */
    public function deleteAction(Service $service, Request $request)
    {
        $form = $this->createDeleteForm($service->getId(), 'service_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($service);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('service'));
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
