<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $user = $this->getUser();
        if ($user) {
            $filters = $this->getDoctrine()->getManager()->getRepository('AppBundle:Filter')->findBy(['user' => $user]);

            return $this->render('AppBundle:Filter:index.html.twig', ['filters' => $filters]);
        }


        return $this->render('AppBundle:default:index.html.twig');
    }
}
