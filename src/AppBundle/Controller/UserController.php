<?php
/**
 * Created by PhpStorm.
 * User: Chyzas
 * Date: 2/10/2017
 * Time: 10:29 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function confirmAction($token)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var User $user */
        $user = $userManager->findUserBy(['confirmationToken' => $token]);
        if ($user) {
            $user->setEnabled(true);
            $userManager->updateUser($user);

            return $this->render('AppBundle:User:confirmed.html.twig');
        }

        return $this->render('@App/User/error.html.twig');
    }
}