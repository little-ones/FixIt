<?php

namespace EntrepreneurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EntrepreneurBundle:Default:index.html.twig');
    }
}
