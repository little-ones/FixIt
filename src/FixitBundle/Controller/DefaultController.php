<?php

namespace FixitBundle\Controller;

use FixitBundle\Entity\Reclamation;
use FixitBundle\Form\ReclamationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FixitBundle:Default:index.html.twig');
    }
    public function aboutusAction()
    {
        return $this->render('@Fixit/Default/aboutus.html.twig');
    }
    public function servicesAction()
    {
        return $this->render('@Fixit/Default/services.html.twig');
    }
    public function galleryAction()
    {
        return $this->render('@Fixit/Default/gallery.html.twig');
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

            return $this->redirectToRoute("fixit_homepage");

        }
        return $this->render('@Fixit/Default/contactus.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
    public function construction_projectAction()
    {
        return $this->render('@Fixit/construction_project/construction_project.html.twig');
    }
}
