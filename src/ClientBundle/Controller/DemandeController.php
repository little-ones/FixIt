<?php

namespace ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use ClientBundle\Entity\Demande;
use ClientBundle\Form\Type\DemandeType;
use ClientBundle\Form\Type\DemandeFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Demande controller.
 *
 */
class DemandeController extends Controller
{
    /**
     * Lists all Demande entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm( DemandeFilterType::class);
        if (!is_null($response = $this->saveFilter($request,$form, 'demande', 'DemandeClientAdmin'))) {
            return $response;
        }
        $qb = $em->getRepository('ClientBundle:Demande')->createQueryBuilder('d');
        $paginator = $this->filter($request,$form, $qb, 'demande');
                return $this->render('ClientBundle:Demande:index.html.twig', array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        ));
    }

    /**
     * Finds and displays a Demande entity.
     *
     */
    public function showAction(Demande $demande)
    {
        $deleteForm = $this->createDeleteForm($demande->getId(), 'DemandeClientAdmin_delete');

        return $this->render('ClientBundle:Demande:show.html.twig', array(
            'demande' => $demande,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Demande entity.
     *
     */
    public function newAction()
    {
        $demande = new Demande();
        $form = $this->createForm(DemandeType::class, $demande);

        return $this->render('ClientBundle:Demande:new.html.twig', array(
            'demande' => $demande,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Demande entity.
     *
     */
    public function createAction(Request $request)
    {

        $demande = new Demande();
        $form = $this->createForm( DemandeType::class, $demande);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $demande->setDate(new \DateTime('now'));
            $em->persist($demande);
            $em->flush();

            return $this->redirect($this->generateUrl('DemandeClientAdmin_show', array('id' => $demande->getId())));
        }

        return $this->render('ClientBundle:Demande:new.html.twig', array(
            'demande' => $demande,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Demande entity.
     *
     */
    public function editAction(Demande $demande)
    {
        $editForm = $this->createForm( DemandeType::class, $demande, array(
            'action' => $this->generateUrl('DemandeClientAdmin_update', array('id' => $demande->getId())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($demande->getId(), 'DemandeClientAdmin_delete');

        return $this->render('ClientBundle:Demande:edit.html.twig', array(
            'demande' => $demande,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Demande entity.
     *
     */
    public function updateAction(Demande $demande, Request $request)
    {
        $editForm = $this->createForm( DemandeType::class, $demande, array(
            'action' => $this->generateUrl('DemandeClientAdmin_update', array('id' => $demande->getId())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('DemandeClientAdmin_edit', array('id' => $demande->getId())));
        }
        $deleteForm = $this->createDeleteForm($demande->getId(), 'DemandeClientAdmin_delete');

        return $this->render('ClientBundle:Demande:edit.html.twig', array(
            'demande' => $demande,
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
        $this->setOrder($request,'demande', $field, $type);

        return $this->redirect($this->generateUrl('DemandeClientAdmin'));
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
     * Deletes a Demande entity.
     *
     */
    public function deleteAction(Demande $demande, Request $request)
    {
        $form = $this->createDeleteForm($demande->getId(), 'DemandeClientAdmin_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($demande);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('DemandeClientAdmin'));
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
