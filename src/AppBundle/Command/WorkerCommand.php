<?php

namespace AppBundle\Command;

use AppBundle\Services\Queue\Message;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WorkerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:queue');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $queue = $this->getContainer()->get('app.sqs_queue');
        $msgProcessor = $this->getContainer()->get('app.message_processor');
        $output->writeln('starting');
        /** @var Message $message */
        $message = $queue->receive();

        if ($message) {
            try {
                $msgProcessor->process($message);
                $queue->delete($message);
                $command = $this->getApplication()->find('swiftmailer:spool:send');
                $command->run($input, $output);
            } catch (\Exception $e) {
                $output->writeln('Error: ' . $message->raw());
                $queue->delete($message);
            }
        }
    }
}
