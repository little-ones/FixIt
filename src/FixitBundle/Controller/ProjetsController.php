<?php

namespace FixitBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use FixitBundle\Entity\Projets;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use FixitBundle\Form\ProjetsType;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Projet controller.
 *
 * @Route("construction_project")
 */
class ProjetsController extends Controller
{
    /**
     * Lists all projet entities.
     *
     * @Route("/", name="projets_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $projets = $em->getRepository('FixitBundle:Projets')->findAll();

        return $this->render('@Fixit/construction_project/construction_project.html.twig', array(
            'projets' => $projets,
        ));
    }

    /**
     * Creates a new projet entity.
     *
     * @Route("/new", name="projets_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $projet = new Projets();
        $form = $this->createForm(ProjetsType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projet);
            $em->flush();

            return $this->redirectToRoute('fixit_construction_project', array('id' => $projet->getId()));
        }

        return $this->render('@Fixit/construction_project/construction_project_new.html.twig', array(
            'projet' => $projet,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a projet entity.
     *
     * @Route("/{id}", name="projets_show")
     * @Method("GET")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $projet = $this->getDoctrine()->getManager()->getRepository(Projets::class)->find($id);
        $deleteForm = $this->createDeleteForm($projet);
        return $this->render('@Fixit/construction_project/construction_project_show.html.twig', array(
            'projet' => $projet,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to delete a projet entity.
     *
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm($id)
    {
        $projet = $this->getDoctrine()->getManager()->getRepository(Projets::class)->find($id);
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('fixit_construction_project_delete', array('id' => $projet->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Displays a form to edit an existing projet entity.
     *
     * @Route("/{id}/edit", name="projets_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */


    public function edit_equipesAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $projet = $em->getRepository(Projets::class)->find($id);
        $form = $this->createForm(ProjetsType::class, $projet);
        $form->setData($projet);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($projet);
            $em->flush();
            return $this->redirectToRoute('fixit_construction_project');
        }
        return $this->render('@Fixit/construction_project/construction_project_edit.html.twig', array('edit_form' => $form->createView()));
    }


    /**
     * Deletes a projet entity.
     *
     * @Route("/{id}/delete", name="projets_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, $id)
    {
        $projet = $this->getDoctrine()->getManager()->getRepository(Projets::class)->find($id);
        $form = $this->createDeleteForm($projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($projet);
            $em->flush();
        }

        return $this->redirectToRoute('fixit_construction_project');
    }


    public function statAction()
    {
        $pieChart = new PieChart();
        $em = $this->getDoctrine();
        $projets = $em->getRepository(Projets::class)->findAll();
        $totalProjets = 0;
        foreach ($projets as $projet) {
            $totalProjets = count($projet->getId());
        }
        $data = array();
        $stat = ['id', 'etat'];
        $data[] = $stat;
        foreach ($projets as $projet) {
            $stat = array();
            array_push($stat, $projet->getEtat(), count($projet->getId()) / $totalProjets);
            $nb = count($projet->getId()) / $totalProjets;
            $stat = [$projet->getEtat(), $nb];
            $data[] = $stat;
        }
        $pieChart->getData()->setArrayToDataTable($data);
        $pieChart->getOptions()->setTitle('Pourcentage des projets terminés et non terminés');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);


        return $this->render('@Fixit/Stat/Stat.html.twig', array('piechart' => $pieChart));
    }

}
