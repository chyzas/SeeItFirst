<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('mail');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = new \Swift_Message('test');
        $message
            ->setFrom('info@skelbimuseklys.lt')
            ->setTo('chyzas@gmail.com')
            ->setBody(
                $this->getContainer()->get('templating')->render('AppBundle:Emails:test.html.twig', ['body' => 'nd']),
                'text/html'
            );
        $mailer = $this->getContainer()->get('mailer');
        $mailer->send($message);
    }
}
