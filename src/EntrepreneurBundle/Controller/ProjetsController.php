<?php

namespace EntrepreneurBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use EntrepreneurBundle\Entity\Projets;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Projet controller.
 *
 */
class ProjetsController extends Controller
{
    /**
     * Lists all projet entities.
     *
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $user->getId();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('EntrepreneurBundle:Projets')->createQueryBuilder('d')->where('d.proprietaire =:id')
            ->setParameter('id',$user)->getQuery();
        $projets = $qb->getResult();

        //$projets = $em->getRepository('EntrepreneurBundle:Projets')->findAll();

        return $this->render('@Entrepreneur/projets/index.html.twig', array(
            'projets' => $projets,
        ));
    }

    /**
     * Creates a new projet entity.
     *
     */
    public function newAction(Request $request)
    {
        $projet = new Projets();
        $form = $this->createForm('EntrepreneurBundle\Form\ProjetsType', $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $projet->setProprietaire($this->getUser()->getId());
            $em->persist($projet);
            $em->flush();

            return $this->redirectToRoute('projets_show', array('id' => $projet->getId()));
        }

        return $this->render('@Entrepreneur/projets/new.html.twig', array(
            'projet' => $projet,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a projet entity.
     *
     */
    public function showAction(Projets $projet)
    {
        $deleteForm = $this->createDeleteForm($projet);

        return $this->render('@Entrepreneur/projets/show.html.twig', array(
            'projet' => $projet,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing projet entity.
     *
     */
    public function editAction(Request $request, Projets $projet)
    {
        $deleteForm = $this->createDeleteForm($projet);
        $editForm = $this->createForm('EntrepreneurBundle\Form\ProjetsType', $projet);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('projets_index');
        }

        return $this->render('EntrepreneurBundle:projets:edit.html.twig', array(
            'projet' => $projet,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a projet entity.
     *
     */
    public function deleteAction(Request $request, Projets $projet)
    {
        $form = $this->createDeleteForm($projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($projet);
            $em->flush();
        }

        return $this->redirectToRoute('projets_index');
    }

    /**
     * Creates a form to delete a projet entity.
     *
     * @param Projets $projet The projet entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Projets $projet)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('projets_delete', array('id' => $projet->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    public function statAction()
    {
        $pieChart = new PieChart();
        $em = $this->getDoctrine();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('EntrepreneurBundle:Projets')->createQueryBuilder('d')->where('d.etat =:etat')
            ->setParameter('etat','Terminé')->getQuery();
        $projets = $qb->getResult();
        $projetsincomplete = $em->getRepository(Projets::class)->findAll();
        $nb = 0;
        $nbIn = 0;
        foreach ($projets as $projet) {
            $nb ++;
        }
        foreach ($projetsincomplete as $projet) {
            $nbIn ++;
        }
       /* $data = array();
        //$stat = ['id', 'etat'];
        $stat = ['Terminé', $nb];
        $data[0] = $stat;
        $stat=['Terminé', $nbIn-$nb];
        $data[1]=$stat;
       /* foreach ($projets as $projet) {
            $stat = array();
            array_push($stat, $projet->getEtat(), count($projet->getId()) / $totalProjets);
            $nb = count($projet->getId()) / $totalProjets;

        }*/
        $pieChart->getData()->setArrayToDataTable(
            [['Task', 'Hours per Day'],
                ['Terminé',     $nb],
                ['Eat',      $nbIn-$nb]
            ]
        );
       // $pieChart->getData()->setArrayToDataTable($data);
        $pieChart->getOptions()->setTitle('Pourcentage des projets terminés et non terminés');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);


        return $this->render('@Entrepreneur/projets/Stat.html.twig', array('piechart' => $pieChart));
    }
}
