<?php

namespace FrontendBundle\Controller;

use AdminUserBundle\Entity\User;
use ClientBundle\Entity\Demande;
use FrontendBundle\Entity\Reclamation;
use FrontendBundle\Form\ReclamationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ServiceBundle\Entity\Service;
use OffreBundle\Form\OffreType;
use OffreBundle\Entity\Offre;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FrontendBundle:Default:index.html.twig');
    }
    public function HomeAction()
    {
        $demandes = $this->getDoctrine()->getRepository(Demande::class)->findAll();
        return $this->render('@Frontend/Default/Home.html.twig', array('demandes'=>$demandes));
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

            return $this->redirectToRoute("fixit_homepage");

        }
        return $this->render('@Frontend/Default/AddOffreClient.html.twig', array(
            'form'   => $form->createView(), 'client'=>$client , 'service' =>$service
        ));

    }

    public function OffresAction()
    {
        $user = $this->getUser();
        $user->getId();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('OffreBundle:Offre')->createQueryBuilder('d')->where('d.User =:id')
            ->setParameter('id',$user)->getQuery();
        $offres = $qb->getResult();
        $services = $this->getDoctrine()->getRepository(Service::class)->findAll();
        return $this->render('@Frontend/Default/MesOffres.html.twig', array('services'=>$services ,'offres' =>$offres));
    }
    public function servicesAction()
    {
        $user = $this->getUser();
        $user->getId();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('ServiceBundle:Service')->createQueryBuilder('d')->where('d.User =:id')
            ->setParameter('id',$user)->getQuery();
        $services = $qb->getResult();
        return $this->render('@Frontend/Default/MesServices.html.twig', array('services'=>$services ));
    }

    public function aboutusAction()
    {
        return $this->render('@Frontend/Default/aboutus.html.twig');
    }

    public function contactusAction(Request $request)
    {
        //return $this->render('@Frontend/Default/contactus.html.twig');
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted()){
            $reclamation->setDate( new \DateTime('now'));
            $reclamation->setUser($this->getUser());
            $reclamation->setEtat("unread");
            $em = $this->getDoctrine()->getManager();
            $em->persist($reclamation);
            $em->flush();

            return $this->redirectToRoute("fixit_homepage");

        }
        return $this->render('@Frontend/Default/contactus.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    public function galleryAction()
    {
        return $this->render('@Frontend/Default/gallery.html.twig');
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

            return $this->redirectToRoute("fixit_MesOffres");

        }
        return $this->render('@Frontend/Default/AddOffre.html.twig', array(
            'form'   => $form->createView(),
        ));
       // return $this->render('@Frontend/Default/AddOffre.html.twig');
    }
    public function profilAction()
    {
        return $this->render('@Frontend/Default/profil.html.twig');
    }
    public function profilImprimerAction()
    {
        $snappy = $this->get("knp_snappy.pdf");
        $html = $this->renderView('@Frontend/Default/profil.html.twig');
        $filename= "custom_pdf_from_twig";

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

    public function AddServiceAction()
    {
        return $this->render('@Frontend/Default/AddService.html.twig');
    }

    public function SuppOffreAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $modele= $em->getRepository(Offre::class)->find($id);

        $em->remove($modele);
        $em->flush();

        return $this->redirectToRoute("fixit_MesOffres");
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
            return $this->redirectToRoute("fixit_MesOffres");
        }
        return $this->render('@Frontend/Default/ModifierOffre.html.twig', array('form' => $form->createView()));
    }

}
