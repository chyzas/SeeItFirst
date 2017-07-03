<?php

namespace AppBundle\Model;

class MessageBody
{
    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $template;

    /**
     * @var array
     */
    private $data;

    public function __construct(string $subject, string $email, string $template, array $data)
    {
        $this->subject = $subject;
        $this->email = $email;
        $this->template = $template;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }


}
