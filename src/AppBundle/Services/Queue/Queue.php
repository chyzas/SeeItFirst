<?php

namespace AppBundle\Services\Queue;

use Aws\Sqs\SqsClient;
use Exception;
use function json_encode;

class Queue
{
    /**
     * @var SqsClient
     */
    private $client;

    /**
     * @var string
     */
    private $url;

    public function __construct(SqsClient $client, string $url)
    {
        $this->client = $client;
        $this->url = $url;
    }

    /**
     * @param Message $message
     */
    public function send(array $data)
    {
        try {
            $this->client->sendMessage(array(
                'QueueUrl' => $this->url,
                'MessageBody' => json_encode($data),
            ));

            return true;
        } catch (\Exception $e) {
            #log it
        }

        return false;
    }

    /**
     * @return Message|bool
     */
    public function receive()
    {
        try {
            $result = $this->client->receiveMessage([
                'QueueUrl' => $this->url
            ]);
            if ($result['Messages'] == null) {
                return false;
            }
            $result_message = array_pop($result['Messages']);

            return new Message($result_message['Body'], $result_message['ReceiptHandle']);
        } catch (Exception $e) {

        }
    }

    public function delete(Message $message)
    {
        try {
            $this->client->deleteMessage(array(
                'QueueUrl' => $this->url,
                'ReceiptHandle' => $message->getReceiptHandler()
            ));

            return true;
        } catch (Exception $e) {
            echo 'Error deleting message from queue ' . $e->getMessage();

            return false;
        }
    }
}
