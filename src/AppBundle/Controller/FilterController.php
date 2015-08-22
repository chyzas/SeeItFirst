<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Filter;
use AppBundle\Form\FilterType;

/**
 * Filter controller.
 *
 */
class FilterController extends Controller
{
    /**
     * Lists all user filters.
     *
     */
    public function indexAction()
    {
        $user = $this->getUser();

        $filters = $this->getDoctrine()->getManager()->getRepository('AppBundle:Filter')->findBy(['user' => $user]);

        return $this->render('AppBundle:Filter:index.html.twig', ['filters' => $filters]);
    }
    /**
     * Creates a new Filter entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Filter();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser());
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('filter_show', array('id' => $entity->getId())));
        }

        return $this->render('AppBundle:Filter:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Filter entity.
     *
     * @param Filter $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Filter $entity)
    {
        $form = $this->createForm(new FilterType(), $entity, array(
            'action' => $this->generateUrl('filter_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Filter entity.
     *
     */
    public function newAction()
    {
        $entity = new Filter();
        $form   = $this->createCreateForm($entity);

        return $this->render('AppBundle:Filter:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Filter entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Filter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Filter entity.');
        }

        return $this->render('AppBundle:Filter:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Deletes a Filter entity.
     *
     */
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

        return $this->redirectToRoute('filter');
    }

    public function resultsAction($id)
    {
        $results = $this->getDoctrine()->getManager()->getRepository('AppBundle:Results')->findBy(['filter' => $id]);

        return $this->render('AppBundle:Filter:results.html.twig', ['results' => $results]);
    }
}
