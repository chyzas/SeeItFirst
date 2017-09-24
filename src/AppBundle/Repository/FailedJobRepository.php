<?php

namespace AppBundle\Repository;

use AppBundle\Entity\FailedJob;
use Doctrine\ORM\EntityRepository;

class FailedJobRepository extends EntityRepository
{
    /**
     * @param $rawBody
     * @param \Exception $exception
     */
    public function log($rawBody, \Exception $exception)
    {
        $entity = new FailedJob();

        $entity
            ->setFailedAt(new \DateTime())
            ->setPayload($rawBody)
            ->setExcepion($exception->getMessage());

        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
        $em->clear();
    }
}