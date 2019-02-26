<?php

namespace ProBundle\Controller;

use ProBundle\Entity\Offre;
use ProBundle\Form\OffreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OffreController extends Controller
{
    public function indexAction()
    {
        return $this->render('ProBundle:Default:index.html.twig');
    }
    public function ListOffreAction()
    {
        $user = $this->getUser();
        $user->getId();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('ProBundle:Offre')->createQueryBuilder('o')->where('o.idPro =:id')
            ->setParameter('id',$user)->getQuery();
        $offre = $qb->getResult();
        return $this->render('@Pro/Offre/mesoffre.html.twig',array('offres'=>$offre));
    }
    public function ajoutOffreAction(Request $request)
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted()){
            $offre->setDate( new \DateTime('now'));
            $offre->setIdPro($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($offre);
            $em->flush();

            return $this->redirectToRoute("pro_offreshow");

        }
        return $this->render('@Pro/Offre/ajouterOffre.html.twig', array(
            'form'   => $form->createView(),
        ));
        // return $this->render('@Frontend/Default/AddOffre.html.twig');
    }
}
