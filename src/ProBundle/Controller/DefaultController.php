<?php

namespace ProBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ProfilType;
use ClientBundle\Entity\Demande;
use ClientBundle\Entity\Reservation;
use ClientBundle\Entity\Service;
use FixitBundle\Entity\Reclamation;
use FixitBundle\Form\ReclamationType;
use ProBundle\Entity\Offre;
use ProBundle\Form\OffreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $demandes = $this->getDoctrine()->getRepository(Demande::class)->findAll();
        $em = $this->getDoctrine()->getManager();
        $qbs = $em->getRepository('ClientBundle:Service')->createQueryBuilder('s')->orderBy('s.id', 'DESC')
            ->setMaxResults(6)->getQuery();
        $service = $qbs->getResult();
        return $this->render('ProBundle:Default:index.html.twig',array('demandes'=>$demandes,'service'=>$service));
    }
    public function profilAction(Request $request)
    {
        $user = $this->getUser();
        $user->getId();
        $u=$this->getDoctrine()->getRepository(User::class)->find($user);
        $form= $this->createForm(ProfilType::class,$u);
        $form=$form->handleRequest($request);
        if( $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $n=$form->get('imageFile')->getData();
            $u->setImageName($n);
            $em->persist($u);
            $em->flush();

        }

        foreach ($u->getRoles() as   $i)
        {
            if($i==="ROLE_CLIENT")
                $role="CLIENT";
            elseif ($i==='ROLE_PRO')
                $role="PROFESSIONNEL";
            elseif($i === "ROLE_VENDEUR")
                $role='VENDEUR';
            else
                $role="";

        }
        if($u->getImageName()==null)
        {
            $u->setImageName('default.jpg');
        }
        return $this->render('@Pro/Default/profilepro.html.twig',array('form'=>$form->createView(),
            'profil'=>$u,'role'=>$role));
    }
    public function ListReservationAction()
    {
        $user = $this->getUser();
        $user->getId();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('ClientBundle:Reservation')->createQueryBuilder('r')->where('r.idPro =:id')
            ->setParameter('id',$user)->getQuery();
        $reservation = $qb->getResult();
        $etat="Acceptation en attente";
        return $this->render('@Pro/ReservationPro/mesreservation.html.twig',array(
            'reservation'=>$reservation,
            'etat'=>$etat));
    }
    public function accepterReservationAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository(Reservation::class)->find($id);
        $reservation->setEtat("Acccepté");
        $em->persist($reservation);
        $em->flush();

        return $this->redirectToRoute('pro_reservation');
    }
    public function ingorerReservationAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository(Reservation::class)->find($id);
        $em->remove($reservation);
        $em->flush();

        return $this->redirectToRoute('pro_reservation');
    }
    public function ContacterAction(Request $request , $id)
    {

        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form = $form->handleRequest($request);
        $offre->setUser($this->getUser());
        $em = $this->getDoctrine()->getManager();
        $demande = $em->getRepository(Demande::class)->find($id);
        $client = $em->getRepository(User::class)->find($demande->getIdClient());
        $service = $em->getRepository(Service::class)->find($demande->getCategorie());
        if ($form->isSubmitted()){

            $offre->setDateAjout( new \DateTime('now'));
            $offre->setClient($client);
            $em->persist($offre);
            $em->flush();

            return $this->redirectToRoute("pro_homepage");

        }
        return $this->render('@Pro/Default/proposOffreClient.html.twig', array(
            'form'   => $form->createView(), 'client'=>$client , 'service' =>$service
        ));

    }
    public function contactusAction(Request $request)
    {

        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted()){
            $reclamation->setDate( new \DateTime('now'));
            $reclamation->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($reclamation);
            $em->flush();

            return $this->redirectToRoute("pro_homepage");

        }
        return $this->render('@Pro/Default/contactus.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
}
