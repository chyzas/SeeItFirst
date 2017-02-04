<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        if ($user) {
            $filters = $this->getDoctrine()->getManager()->getRepository('AppBundle:Filter')->findBy(['user' => $user]);

            return $this->render('AppBundle:Filter:index.html.twig', ['filters' => $filters]);
        }

        $form = $this->createFormBuilder()
            ->add('url', 'text', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'col-sm-2 control-label']
            ])
            ->add('name', 'text', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'col-sm-2 control-label']
            ])
            ->add('email', 'text', [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'col-sm-2 control-label']
            ])
            ->add('save', 'submit', array('label' => 'Submit', 'attr' => array('class'=>'btn-default')))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $url = $form->getData()['url'];
            $email = $form->getData()['email'];
            $name = $form->getData()['name'];
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $password = substr($tokenGenerator->generateToken(), 0, 5);

            try {
                $userManager = $this->container->get('fos_user.user_manager');
                $newUser = new User();
                $newUser->setEmail($email);
                $newUser->setUsername($email);
                $newUser->setUsernameCanonical($email);
                $newUser->setEnabled(true);
                $newUser->setPlainPassword($password);
                $userManager->updateUser($newUser);

                $filterManager = $this->get('filter_manager');
                $filterManager->addFilter($newUser, $url, $name);

                $this->addFlash('notice', 'User created and filter has been added. Password: ' . $password);

            } catch (\Exception $e) {
                $this->addFlash('error', 'Something went wrong: ' . $e->getMessage());
            }
        }

        return $this->render('AppBundle:default:index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
