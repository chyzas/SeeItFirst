<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use Swift_Mailer;
use Symfony\Bundle\TwigBundle\TwigEngine;

class Mail
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var TwigEngine
     */
    private $templating;

    /**
     * Mail constructor.
     * @param Swift_Mailer $mailer
     * @param TwigEngine $templating
     */
    public function __construct(Swift_Mailer $mailer, TwigEngine $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    /**
     * @param User $user
     */
    public function sendConfirmation(User $user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Confirmation Email')
            ->setFrom('send@example.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render(
                    '@App/emails/registration.html.twig',
                    [
                        'pass' => $user->getTempPlainPassword(),
                        'token' => $user->getConfirmationToken()
                    ]
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }
}