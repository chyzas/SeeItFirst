<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
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

        $form = $this->createFormBuilder()
            ->add('url', 'text', [
                'label' => 'filter_form.query_url',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'col-sm-2 control-label']
            ])
            ->add('name', 'text', [
                'label' => 'filter_form.query_name',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'col-sm-2 control-label']
            ])
            ->add('email', 'text', [
                'label' => 'main.email',
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'col-sm-2 control-label']
            ])
            ->add('save', 'submit', array('label' => 'main.save', 'attr' => array('class'=>'btn-default')))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userManager = $this->container->get('fos_user.user_manager');
            $url = $form->getData()['url'];
            $email = $form->getData()['email'];

            if ($userManager->findUserBy(['email' => $email])) {
                $this->addFlash('error', 'User already exist: ' . $email);

                return $this->redirect($request->headers->get('referer'));
            } else {
                $name = $form->getData()['name'];
                $tokenGenerator = $this->container->get('fos_user.util.token_generator');
                $password = substr($tokenGenerator->generateToken(), 0, 5);
                try {
                    $newUser = new User();
                    $newUser->setEmail($email);
                    $newUser->setUsername($email);
                    $newUser->setUsernameCanonical($email);
                    $newUser->setEnabled(false);
                    $newUser->setPlainPassword($password);
                    $newUser->setConfirmationToken(substr($tokenGenerator->generateToken(), 0, 7));
                    $newUser->setTempPlainPassword($password);

                    $userManager->updateUser($newUser);

                    $filterManager = $this->get('filter_manager');
                    $filterManager->addFilter($newUser, $url, $name);

                    $this->get('mail')->sendConfirmation($newUser);

                    $this->addFlash('notice', 'User and filter has been created. Please confirm!');

                } catch (\Exception $e) {
                    $this->addFlash('error', 'Something went wrong: ' . $e->getMessage());
                }
            }
        }

        return $this->render('AppBundle:default:index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
