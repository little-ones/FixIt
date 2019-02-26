<?php

namespace AdminUserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminUserBundle:Default:index.html.twig');
    }
}
