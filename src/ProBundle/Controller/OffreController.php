<?php

namespace ProBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ProBundle\Entity\Offre;
use ProBundle\Form\Type\OffreType;
use ProBundle\Form\Type\OffreFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Offre controller.
 *
 */
class OffreController extends Controller
{
    /**
     * Lists all Offre entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm( OffreFilterType::class);
        if (!is_null($response = $this->saveFilter($request,$form, 'offre', 'offre'))) {
            return $response;
        }
        $qb = $em->getRepository('ProBundle:Offre')->createQueryBuilder('o');
        $paginator = $this->filter($request,$form, $qb, 'offre');
                return $this->render('ProBundle:Offre:index.html.twig', array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        ));
    }

    /**
     * Finds and displays a Offre entity.
     *
     */
    public function showAction(Offre $offre)
    {
        $deleteForm = $this->createDeleteForm($offre->getId(), 'offre_delete');

        return $this->render('ProBundle:Offre:show.html.twig', array(
            'offre' => $offre,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Offre entity.
     *
     */
    public function newAction()
    {
        $offre = new Offre();
        $form = $this->createForm( OffreType::class, $offre);

        return $this->render('ProBundle:Offre:new.html.twig', array(
            'offre' => $offre,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Offre entity.
     *
     */
    public function createAction(Request $request)
    {
        $offre = new Offre();
        $form = $this->createForm( OffreType::class, $offre);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($offre);
            $em->flush();

            return $this->redirect($this->generateUrl('offre_show', array('id' => $offre->getId())));
        }

        return $this->render('ProBundle:Offre:new.html.twig', array(
            'offre' => $offre,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Offre entity.
     *
     */
    public function editAction(Offre $offre)
    {
        $editForm = $this->createForm( OffreType::class, $offre, array(
            'action' => $this->generateUrl('offre_update', array('id' => $offre->getId())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($offre->getId(), 'offre_delete');

        return $this->render('ProBundle:Offre:edit.html.twig', array(
            'offre' => $offre,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Offre entity.
     *
     */
    public function updateAction(Offre $offre, Request $request)
    {
        $editForm = $this->createForm( OffreType::class, $offre, array(
            'action' => $this->generateUrl('offre_update', array('id' => $offre->getId())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('offre_edit', array('id' => $offre->getId())));
        }
        $deleteForm = $this->createDeleteForm($offre->getId(), 'offre_delete');

        return $this->render('ProBundle:Offre:edit.html.twig', array(
            'offre' => $offre,
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
        $this->setOrder('offre', $field, $type);

        return $this->redirect($this->generateUrl('offre'));
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
     * Deletes a Offre entity.
     *
     */
    public function deleteAction(Offre $offre, Request $request)
    {
        $form = $this->createDeleteForm($offre->getId(), 'offre_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($offre);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('offre'));
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
