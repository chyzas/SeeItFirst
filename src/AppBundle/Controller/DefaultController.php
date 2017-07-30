<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
use AppBundle\Form\FirstQueryType;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('AppBundle:default:index.html.twig', [
            'form' => $this->createForm(new FirstQueryType())->createView(),
            'urls' => $this->getAvailableUrls()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function submitAction(Request $request)
    {
        if ($request->isXmlHttpRequest() && $request->getMethod() == Request::METHOD_POST) {
            $userManager = $this->container->get('fos_user.user_manager');
            $formData = $request->request->all();

            $email = $formData['email'];
            $url = $formData['url'];
            $name = $formData['name'];

            if (!($email && $url && $name)) {
                return new JsonResponse([
                    'message' => $this->get('translator')->trans('errors.fill_missing_fields')
                ], Response::HTTP_BAD_REQUEST);
            }

            $user = $userManager->findUserBy(['email' => $email]);
            if (!$user) {
                $user = $this->get('user_service')->createUser($email);
            }

            $em = $this->get('doctrine.orm.entity_manager');
            $em->getConnection()->beginTransaction();
            try {
                $filterManager = $this->get('filter_manager');
                $filterManager->addFilter($user, $url, $name);
                $em->getConnection()->commit();

                return new JsonResponse([
                    'message' => $this->get('translator')->trans('registration.flash.confirm_filter', ['%email%' => $user->getEmail()])
                ]);

            } catch (ClientException $e) {
                $em->getConnection()->rollBack();

                return new JsonResponse([
                        'message' => $this->get('translator')->trans('errors.bad_request')
                    ], Response::HTTP_BAD_REQUEST
                );
            }
            catch (\Exception $e) {
                $em->getConnection()->rollBack();

                return new JsonResponse([
                    'message' => $e->getMessage()
                ], Response::HTTP_BAD_REQUEST);
            }
        }

        return new JsonResponse([
            'message' => $this->get('translator')->trans('errors.something_went_wrong')
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @return array
     */
    private function getAvailableUrls()
    {
        return $this->get('doctrine.orm.entity_manager')->getRepository(Site::class)->findAll();
    }
}
