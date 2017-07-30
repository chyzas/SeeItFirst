<?php

namespace AppBundle\Services\Queue;

use AppBundle\Model\MessageBody;

class Message
{
    /**
     * @var string
     */
    private $body;

    /**
     * @var string
     */
    private $receiptHandler;

    public function __construct(string $body, string $receiptHandler)
    {
        $this->body = $body;
        $this->receiptHandler = $receiptHandler;
    }


    public function getBody(): MessageBody
    {
        $body = json_decode($this->body, 'true');

        return new MessageBody(
            $body['subject'],
            $body['email'],
            $body['template'],
            $body['data']
        );
    }

    /**
     * @return string
     */
    public function getReceiptHandler(): string
    {
        return $this->receiptHandler;
    }
}
