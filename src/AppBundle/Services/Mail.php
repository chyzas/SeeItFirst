<?php

namespace AppBundle\Services;

use AppBundle\Entity\User;
use Swift_Mailer;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Translation\TranslatorInterface;

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
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Mail constructor.
     * @param Swift_Mailer $mailer
     * @param TwigEngine $templating
     * @param TranslatorInterface $translator
     */
    public function __construct(Swift_Mailer $mailer, TwigEngine $templating, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->translator = $translator;
    }

    /**
     * @param User $user
     */
    public function sendConfirmation(User $user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($this->translator->trans('emails.confirmation.subject'))
            ->setFrom('send@example.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render(
                    '@App/emails/confirmation.html.twig',
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