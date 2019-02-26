<?php

namespace ClientBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ProfilType;
use ClientBundle\Entity\Demande;
use ClientBundle\Form\Type\DemandeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ClientBundle:Default:home.html.twig');
    }
    public function ddAction()
    {
        return $this->render('ClientBundle:Default:index.html.twig');
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
        $qb = $em->getRepository('ProBundle:Offre')->findAll();

        return $this->render('@Client/Default/listoffre.html.twig',array('offres'=>$qb));
    }
}
