<?php

namespace ProBundle\Controller;

use ClientBundle\Entity\Service;
use ClientBundle\Form\ServiceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ServiceCController extends Controller
{
    public function indexAction()
    {
        return $this->render('ProBundle:Default:index.html.twig');
    }
    public function ajoutServiceAction(Request $request)
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted()){
            $service->setDate( new \DateTime('now'));
            $service->setIdUser($this->getUser());
            $service->setImageName('defaultservice.png');
            if($form->get('imageFile')->getData()!== null)
            {   $n=$form->get('imageFile')->getData();
                    $service->setImageName($n);
                    echo $n;
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();

            return $this->redirectToRoute("pro_offreajout");

        }



        return $this->render('@Pro/Service/ajoutService.html.twig',array('form'=>$form->createView()));
    }
    public function ModifierServiceAction($id ,  Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $service = $em->getRepository(Service::class)->find($id);

        $form = $this->createForm(ServiceType::class, $service);
        $form->setData($service);
        $form = $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $service->setDate( new \DateTime('now'));

            $n=$form->get('imageFile')->getData();
            $service->setImageName($n);
            $em->persist($service);
            $em->flush();
            return $this->redirectToRoute("pro_offreajout");
        }
        if($service->getImageName()==null) {
            $service->setImageName('defaultservice.png');
        }
        return $this->render('@Pro/Service/modifierService.html.twig', array('form' => $form->createView()));
    }
    public function SuppServiceAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $modele= $em->getRepository(Service::class)->find($id);

        $em->remove($modele);
        $em->flush();

        return $this->redirectToRoute("pro_offreshow");
    }
    public function ListservicesAction()
    {
        $user = $this->getUser();
        $user->getId();
        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('ClientBundle:Service')->createQueryBuilder('d')
            ->where('d.idUser =:id')
            ->setParameter('id',$user)->getQuery();
        $services = $qb->getResult();
        return $this->render('@Pro/Service/mesServices.html.twig', array('services'=>$services ));
    }
}
