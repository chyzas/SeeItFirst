<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Util\TokenGenerator;

class UserService
{
    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    /**
     * UserService constructor.
     * @param UserManager $userManager
     * @param TokenGenerator $tokenGenerator
     */
    public function  __construct(UserManager $userManager, TokenGenerator $tokenGenerator)
    {
        $this->userManager = $userManager;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function createUser($email)
    {
        $password = substr($this->tokenGenerator->generateToken(), 0, 5);
        $user = new User();
        $user->setEmail($email);
        $user->setUsername($email);
        $user->setUsernameCanonical($email);
        $user->setEnabled(true);
        $user->setPlainPassword($password);
        $user->setConfirmationToken(substr($this->tokenGenerator->generateToken(), 0, 7));
        $user->setTempPlainPassword($password);
        $this->userManager->updateUser($user);

        return $user;
    }
}