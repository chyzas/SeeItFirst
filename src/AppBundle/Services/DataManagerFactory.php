<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 17.9.26
 * Time: 20.45
 */

namespace AppBundle\Services;


use Doctrine\ORM\EntityManager;

class DataManagerFactory
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * DataManagerFactory constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {

        $this->entityManager = $entityManager;
    }

    public function get($template, $data)
    {
        switch ($template) {
            case 'results':
                return new ResultsDataManager($data, $this->entityManager);
            default:
                return new DefaultDataManager($data);
        }
    }
}