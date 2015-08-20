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

    public function filterAction()
    {
        $user = $this->getUser();

        $filters = $this->getDoctrine()->getManager()->getRepository('AppBundle:Filter')->findBy(['user' => $user]);

        return $this->render('@App/default/filter.html.twig', ['filters' => $filters]);
    }

    public function createAction()
    {
        $form = $this
            ->createFormBuilder()
            ->add('site', 'choice', array(
                'mapped'  => false,
                'choices' => $this->buildChoices()))
            ->getForm();

        return $this->render('@App/default/create.html.twig', ['form' => $form->createView()]);
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $filter = $em->getRepository('AppBundle:Filter')->find($id);

        if (!$filter) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $em->remove($filter);
        $em->flush();

        return $this->redirectToRoute('filters');
    }

    protected function buildChoices() {
        $choices          = [];
        $table2Repository = $this->getDoctrine()->getRepository('AppBundle:Site');
        $table2Objects    = $table2Repository->findAll();

        foreach ($table2Objects as $table2Obj) {
            $choices[$table2Obj->getId()] = $table2Obj->getName();
        }

        return $choices;
    }
}
