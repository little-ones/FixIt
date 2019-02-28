<?php

namespace ClientBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ProfilType;
use CabinetBundle\Entity\Evenement;
use CabinetBundle\Entity\Formation;
use CabinetBundle\Entity\Participation;
use FixitBundle\Entity\Reclamation;
use FixitBundle\Form\ReclamationType;
use ProBundle\Entity\Offre;
use ClientBundle\Entity\Demande;
use ClientBundle\Form\Type\DemandeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qbs = $em->getRepository('ClientBundle:Service')->createQueryBuilder('s')->orderBy('s.id', 'DESC')
            ->setMaxResults(6)->getQuery();
        $service = $qbs->getResult();

        $qb = $em->getRepository('ProBundle:Offre')->findAll();


        return $this->render('@Client/Default/listoffre.html.twig',array('offres'=>$qb,'service'=>$service));
    }
    public function offrerecuAction()
    {
        $user = $this->getUser();
        $user->getId();
        $em = $this->getDoctrine()->getManager();

        $qbs = $em->getRepository('ClientBundle:Service')->createQueryBuilder('s')
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(6)->getQuery();
        $service = $qbs->getResult();

        $qb=  $em->getRepository('ProBundle:Offre')->createQueryBuilder('o')
            ->where('o.Client=:id')
            ->setParameter('id',$user)->getQuery();
        $service = $qbs->getResult();
        $offre=$qb->getResult();

        return $this->render('@Client/Default/offresrecu.html.twig',array('offres'=>$offre,'service'=>$service));
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
        return $this->render('@Client/Default/profile.html.twig',array('form'=>$form->createView(),
            'profil'=>$u,'role'=>$role));
    }
    /******************Offre***********************************/


    public function ListOffreAction()
    {

        $em = $this->getDoctrine()->getManager();

        $qbs = $em->getRepository('ClientBundle:Service')->createQueryBuilder('s')->orderBy('s.id', 'DESC')
        ->setMaxResults(6)->getQuery();
        $service = $qbs->getResult();

        $qb = $em->getRepository('ProBundle:Offre')->findAll();


        return $this->render('@Client/Default/listoffre.html.twig',array('offres'=>$qb,'service'=>$service));
    }
    public function profilUSERAction($id)
    {

        $o=$this->getDoctrine()->getRepository(Offre::class)->find($id);
        $u=$this->getDoctrine()->getRepository(User::class)->find($o->getUser());


        return $this->render('@Client/profil/profile.html.twig',array('profil'=>$u));
    }
    public function profilImprimerAction($id)
    {
        $snappy = $this->get("knp_snappy.pdf");
        $profil=$this->getDoctrine()->getRepository(User::class)->find($id);
        $html = $this->renderView('@Client/profil/profile.html.twig',array('profil'=>$profil));
        $filename= "professionel";

        return new Response(
            $snappy->getOutputFromHtml($html,array(
                'lowquality' => false,
                'encoding' => 'utf-8',
            )),
            200,
            array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"'
            )
        );
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

            return $this->redirectToRoute("client_offreslist");

        }
        return $this->render('@Client/Default/contactus.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
    public function ListEvenementAction()
    {
        $em = $this->getDoctrine()->getManager();

        $evenements = $em->getRepository('CabinetBundle:Evenement')->findAll();

        return $this->render('@Client/Formation/listeEvenement.html.twig', array(
            'evenements' => $evenements,
        ));
    }
    public function FormationConsultAction($id)
    {
        $formation = $this->getDoctrine()->getManager()->getRepository(Formation::class)->find($id);

        return $this->render('@Client/Formation/consultationFormation.html.twig', array(
            'formation' => $formation
        ));
    }
    public function EvenementConsultAction($id)
    {
        $evenement = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($id);

        return $this->render('@Client/Formation/consultationEvenement.html.twig', array(
            'evenement' => $evenement
        ));
    }
    public function ParticiperAction( $id)
    {
        $m = $this->getDoctrine()->getManager();
        $evemenet = $m->getRepository(evenement::class)->find($id);
        if ($evemenet->getNbreParticipant() > 0) {
            $Participation = new Participation();
            $Participation->setuser($this->getUser());
            $Participation->setevenement($evemenet);
            $evemenet->setNbreParticipant($evemenet->getNbreParticipant()-1);
            $m->persist($Participation);
            $m->flush();
            $m->persist($evemenet);
            $m->flush();
            return $this->redirectToRoute('client_EvenementList');
        } else {
            $this->addFlash('success','Vous ne pouvez pas participer à votre évennement !!');
            return $this->redirectToRoute('client_EvenementList');
        }
    }
}
