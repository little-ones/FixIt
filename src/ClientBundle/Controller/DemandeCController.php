<?php

namespace ClientBundle\Controller;

use ClientBundle\Entity\Demande;
use ClientBundle\Form\DemandeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DemandeCController extends Controller
{

    public function ListDemandeAction()
    {
        $user = $this->getUser();
        $user->getId();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('ClientBundle:Demande')->createQueryBuilder('d')->where('d.idClient =:id')
            ->setParameter('id',$user)->getQuery();
        $demande = $qb->getResult();
        return $this->render('@Client/Demande/mesdemandes.html.twig',array('demande'=>$demande,'usrname'=>$user));
    }
    public function ajouterAction(Request $request)
    {
        $user = $this->getUser();
        $user->getId();
        $demande = new Demande();
        $form = $this->createForm(DemandeType::class, $demande);
        $form=$form->handleRequest($request);
        if ($form->isValid())
        {
            $demande->setIdClient($user);
            $demande->setDate(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($demande);
            $em->flush();
            return $this->redirectToRoute('client_demandeshow');
        }
        return $this->render('@Client/Demande/ajouter.html.twig',array('form'=>$form->createView()));

    }
    public function ModifierAction($id,Request $request)
    {
        $d=$this->getDoctrine()->getRepository(Demande::class)->find($id);
        $form= $this->createForm(DemandeType::class,$d);
        $form->setData($d);
        $form=$form->handleRequest($request);
        if($form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($d);
            $em->flush();
            return $this->redirectToRoute('client_demandeshow');
        }
        return $this->render('@Client/Demande/modifier.html.twig',array('form'=>$form->createView()));
    }
    public function DeleteAction(Request $request,$id)
    {
        $demande = $this->getDoctrine()->getRepository(Demande::class)->find($id);


        $em = $this->getDoctrine()->getManager();
        $em->remove($demande);
        $em->flush();

        return $this->redirectToRoute('client_demandeshow');



    }
    /*------------------------------------------------------------*/
    public function ListOffreAction()
    {

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('ProBundle:Offre')->findAll();

        return $this->render('@Client/Default/listoffre.html.twig',array('offres'=>$qb));
    }
}
