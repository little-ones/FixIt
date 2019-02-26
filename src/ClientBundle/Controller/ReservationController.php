<?php

namespace ClientBundle\Controller;

use ClientBundle\Entity\Demande;
use ClientBundle\Entity\Reservation;
use ClientBundle\Form\ReservationType;
use ClientBundle\Form\Type\DemandeType;
use ProBundle\Entity\Offre;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ReservationController extends Controller
{
    public function indexAction()
    {
        return $this->render('ClientBundle:Default:home.html.twig');
    }
    public function profilAction()
    {
        return $this->render('@Client/Default/profile.html.twig');
    }

    public function ajouterAction(Request $request , $id)
    {
        $user = $this->getUser();
        $user->getId();
        $reservation = new Reservation();
        $offre=$this->getDoctrine()->getRepository(Offre::class)->find($id);
        $form = $this->createForm(ReservationType::class, $reservation);
        $form=$form->handleRequest($request);

        $reservation->setIdPro($offre->getIdPro());
        $reservation->setBudget($offre->getBudget());
        $reservation->setService($offre->getService()->getCategorie());

        if ($form->isSubmitted())
        {
            $reservation->setIdClient($user);
            $reservation->setEtat('Non-accepter');
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            return $this->redirectToRoute('client_reservationlist');
        }
        return $this->render('@Client/Reservation/ajoutreservation.html.twig',array('form'=>$form->createView(),
            'reserv'=>$reservation));

    }
    public function ListReservationAction()
    {
        $user = $this->getUser();
        $user->getId();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('ClientBundle:Reservation')->createQueryBuilder('r')->where('r.idClient =:id')
            ->setParameter('id',$user)->getQuery();
        $reservation = $qb->getResult();
        return $this->render('@Client/Reservation/mesreservation.html.twig',array('reservation'=>$reservation,'usrname'=>$user));
    }
    public function modifierAction(Request $request , $id)
    {
        $r=$this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $form= $this->createForm(ReservationType::class,$r);
        $form->setData($r);
        $form=$form->handleRequest($request);
        if($form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($r);
            $em->flush();
            return $this->redirectToRoute('client_reservationlist');
        }
        return $this->render('@Client/Reservation/modifierreservation.html.twig',array('form'=>$form->createView(),
            'reserv'=>$r));

    }
    public function DeleteAction(Request $request,$id)
    {
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($reservation);
        $em->flush();
        return $this->redirectToRoute('client_reservationlist');
    }

}
