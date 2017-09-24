<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FailedJobRepository")
 * @ORM\Table(name="failed_job")
 */
class FailedJob
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="payload", type="text")
     */
    private $payload;

    /**
     * @var string
     *
     * @ORM\Column(name="excepion", type="text")
     */
    private $excepion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $failedAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return FailedJob
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    /**
     * @param string $payload
     * @return FailedJob
     */
    public function setPayload(string $payload): FailedJob
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @return string
     */
    public function getExcepion(): string
    {
        return $this->excepion;
    }

    /**
     * @param string $excepion
     * @return FailedJob
     */
    public function setExcepion(string $excepion): FailedJob
    {
        $this->excepion = $excepion;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFailedAt(): \DateTime
    {
        return $this->failedAt;
    }

    /**
     * @param \DateTime $failedAt
     * @return FailedJob
     */
    public function setFailedAt(\DateTime $failedAt): FailedJob
    {
        $this->failedAt = $failedAt;
        return $this;
    }
}