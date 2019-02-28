<?php

namespace CabinetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CabinetBundle:Default:index.html.twig');
    }
}
