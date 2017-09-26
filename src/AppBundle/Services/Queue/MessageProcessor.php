<?php

namespace AppBundle\Services\Queue;

use AppBundle\Model\MessageBody;
use AppBundle\Services\DataManagerFactory;
use Monolog\Logger;
use Symfony\Bundle\TwigBundle\TwigEngine;

class MessageProcessor
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var TwigEngine
     */
    private $engine;

    /**
     * @var DataManagerFactory
     */
    private $dataManager;

    public function __construct(\Swift_Mailer $mailer, TwigEngine $engine, DataManagerFactory $dataManager)
    {
        $this->mailer = $mailer;
        $this->engine = $engine;
        $this->dataManager = $dataManager;
    }

    public function process(Message $message)
    {
        $logger= new Logger('mailer');
        /** @var MessageBody $body */
        $body = $message->getBody();
        try {
            $mail = new \Swift_Message($body->getSubject());
            $mail
                ->setFrom('info@skelbimuseklys.lt')
                ->setTo([$body->getEmail()])
                ->setBody(
                    $this->engine->render(
                        'AppBundle:Emails:'.$body->getTemplate().'.html.twig',
                        ['data' => $this->dataManager->get($body->getTemplate(), $body->getData())->getData()]
                    ),
                    'text/html'
                );
            $this->mailer->send($mail);
        } catch (\Exception $e) {
            $logger->error($e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}
