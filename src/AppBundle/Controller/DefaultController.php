<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
use AppBundle\Form\FirstQueryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        if ($user) {
            $filters = $this->getDoctrine()->getManager()->getRepository('AppBundle:Filter')->findBy(['user' => $user]);

            return $this->render('AppBundle:Filter:index.html.twig', ['filters' => $filters]);
        }

        $form = $this->createForm(new FirstQueryType());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager = $this->container->get('fos_user.user_manager');
            $formData = $form->getData();
            $email = $formData['email'];

            if ($userManager->findUserBy(['email' => $email])) {
                $this->addFlash('danger', $this->get('translator')->trans('errors.user_exist') . $email);

                return $this->redirect($request->headers->get('referer'));
            } else {
                try {
                    $newUser = $this->get('user_service')->createUser($email);
                    $filterManager = $this->get('filter_manager');
                    $filterManager->addFilter($newUser, $formData['url'], $formData['name']);
                    $this->get('mail')->sendConfirmation($newUser);
                    $this->addFlash('notice', $this->get('translator')->trans('filter_form.saved'));
                } catch (\Exception $e) {
                    $this->addFlash('danger', $this->get('translator')->trans($e->getMessage()));
                }
            }
        }

        return $this->render('AppBundle:default:index.html.twig', [
            'form' => $form->createView(),
            'urls' => $this->getAvailableUrls()
        ]);
    }

    /**
     * @return array
     */
    private function getAvailableUrls()
    {
        return $this->get('doctrine.orm.entity_manager')->getRepository(Site::class)->findAll();
    }
}
