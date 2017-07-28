<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
use AppBundle\Form\FirstQueryType;
use AppBundle\Services\Queue\Message;
use GuzzleHttp\Exception\ClientException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    public function testAction()
    {
        $queue = $this->get('app.sqs_queue');
            /** @var Message $message */
        $message = $queue->receive();
        $body = $message->getBody();
        return $this->render('@App/Emails/results.html.twig', ['data' => $body->getData()]);
    }


    public function indexAction(Request $request): Response
    {
        $form = $this->createForm(new FirstQueryType());

        $form->handleRequest($request);

        if (!($form->isSubmitted() && $form->isValid())) {

            return $this->render('AppBundle:default:index.html.twig', [
                'form' => $form->createView(),
                'urls' => $this->getAvailableUrls()
            ]);
        }

        $userManager = $this->container->get('fos_user.user_manager');
        $formData = $form->getData();
        $email = $formData['email'];

        $user = $userManager->findUserBy(['email' => $email]);
        if (!$user) {
            $user = $this->get('user_service')->createUser($email);
        }

        $em = $this->get('doctrine.orm.entity_manager');
        $em->getConnection()->beginTransaction();
        try {
            $filterManager = $this->get('filter_manager');
            $filterManager->addFilter($user, $formData['url'], $formData['name']);
            $em->getConnection()->commit();

            $this->addFlash('success', $this->get('translator')->trans('registration.flash.confirm_filter', ['%email%' => $user->getEmail()]));
        } catch (ClientException $e) {
            $em->getConnection()->rollBack();
            $this->addFlash('danger', $this->get('translator')->trans('errors.bad_request'));
        }
        catch (\Exception $e) {
            $em->getConnection()->rollBack();
            $this->addFlash('danger', $e->getMessage());
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
