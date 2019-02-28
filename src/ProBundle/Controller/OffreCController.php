<?php

namespace ProBundle\Controller;

use ClientBundle\Entity\Service;
use ProBundle\Entity\Offre;
use ProBundle\Form\OffreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OffreCController extends Controller
{
    public function indexAction()
    {
        return $this->render('ProBundle:Default:index.html.twig');
    }
    public function mesOffreAction()
    {
        $user = $this->getUser();
        $user->getId();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('ProBundle:Offre')->createQueryBuilder('d')->where('d.User =:id')
            ->setParameter('id',$user)->getQuery();
        $offres = $qb->getResult();
        $services = $this->getDoctrine()->getRepository(Service::class)->findAll();
        return $this->render('@Pro/Offre/mesoffre.html.twig', array('services'=>$services ,'offres' =>$offres));
    }
    public function AddOffreAction(Request $request)
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted()){
            $offre->setDateAjout( new \DateTime('now'));
            $offre->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($offre);
            $em->flush();

            return $this->redirectToRoute("pro_offreajout");

        }
        return $this->render('@Pro/Offre/ajouterOffre.html.twig', array(
            'form'   => $form->createView(),
        ));

    }
    public function ModifierOffreAction($id ,  Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $offre = $em->getRepository(Offre::class)->find($id);

        $form = $this->createForm(OffreType::class, $offre);
        $form->setData($offre);

        $form = $form->handleRequest($request);


        if ($form->isValid()) {
            $em->persist($offre);
            $em->flush();
            return $this->redirectToRoute("pro_offreajout");
        }
        return $this->render('@Pro/Offre/modifierOffre.html.twig', array('form' => $form->createView()));
    }
    public function SuppOffreAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $modele= $em->getRepository(Offre::class)->find($id);

        $em->remove($modele);
        $em->flush();

        return $this->redirectToRoute("pro_offreshow");
    }
}
