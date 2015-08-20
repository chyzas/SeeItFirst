<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppBundle:default:index.html.twig');
    }

//    public function createAction()
//    {
//        $form = $this
//            ->createFormBuilder()
//            ->add('site', 'choice', array(
//                'mapped'  => false,
//                'choices' => $this->buildChoices()))
//            ->getForm();
//
//        return $this->render('@App/default/create.html.twig', ['form' => $form->createView()]);
//    }
//
//    protected function buildChoices() {
//        $choices          = [];
//        $table2Repository = $this->getDoctrine()->getRepository('AppBundle:Site');
//        $table2Objects    = $table2Repository->findAll();
//
//        foreach ($table2Objects as $table2Obj) {
//            $choices[$table2Obj->getId()] = $table2Obj->getName();
//        }
//
//        return $choices;
//    }
}
