<?php

namespace FormationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FormationBundle:Default:index.html.twig');
    }
    public function aboutusAction()
    {
        return $this->render('FormationBundle:Default:aboutus.html.twig');
    }
    public function servicesAction()
    {
        return $this->render('FormationBundle:Default:services.html.twig');
    }
    public function galleryAction()
    {
        return $this->render('FormationBundle:Default:gallery.html.twig');
    }
    public function contactusAction()
    {
        return $this->render('@Formation/Default/contactus.html.twig');
    }
}
