<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\ProfilType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $user=null;
        if( $this->container->get( 'security.authorization_checker' )->isGranted( 'IS_AUTHENTICATED_FULLY' ) )
        {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            if(in_array('ROLE_ADMIN',$user->getRoles()))
            {
                return $this->redirectToRoute('admin_homepage' );
            }
            elseif (in_array('ROLE_PRO',$user->getRoles()))
            {
                return $this->redirectToRoute('pro_homepage');
            }
            elseif (in_array('ROLE_CLIENT',$user->getRoles()))
            {
                return $this->redirectToRoute('client_offreslist');
            }
            elseif (in_array('ROLE_ENTREPRENEUR',$user->getRoles()))
            {
                return $this->redirectToRoute('entrepreneur_homepage');
            }
            elseif (in_array('ROLE_CABINET',$user->getRoles()))
            {
                return $this->redirectToRoute('cabinet_homepage');
            }
        }
        return $this->render('@Fixit/Default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

}
