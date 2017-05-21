<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Filter;
use AppBundle\Form\FirstQueryType;
use Symfony\Component\HttpFoundation\Response;

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
        $filterManager = $this->get('filter_manager');
        try {
            $filter = $filterManager->addFilter($this->getUser(), $request->get('url'), $request->get('name'));
        } catch (\Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage()
            ],
                Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(
            [
                'id' => $filter->getId(),
                'name' => $filter->getFilterName(),
                'url' => $filter->getUrl()
            ]
        );
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
        $form = $this->createForm(new FirstQueryType(), $entity, array(
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
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $filter = $em->getRepository('AppBundle:Filter')->find($id);

        if (!$filter) {
            throw $this->createNotFoundException(
                $this->get('translator')->trans('errors.filter.not_found')
            );
        }

        if ($filter->getUser() != $user) {
            return $this->redirectToRoute('fos_user_security_login');
        }


        $em->remove($filter);
        $em->flush();

        return $this->redirectToRoute('filter');
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function resultsAction(Request $request)
    {
        $query = $this->getDoctrine()->getManager()->getRepository('AppBundle:Results')->findBy(['filter' => $request->get('id')]);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20

        );

        return $this->render('AppBundle:Filter:results.html.twig', ['pagination' => $pagination]);
    }
}
