<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 17.9.26
 * Time: 20.12
 */

namespace AppBundle\Services;


use AppBundle\Entity\Filter;
use AppBundle\Entity\Results;
use Doctrine\ORM\EntityManager;

class ResultsDataManager implements DataManagerInterface
{
    /**
     * @var
     */
    private $data;
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct($data, EntityManager $entityManager)
    {
        $this->data = $data;
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $filterRepository = $this->entityManager->getRepository(Filter::class);
        $resultsRepository = $this->entityManager->getRepository(Results::class);


        $filter = $filterRepository->findOneBy(['id' => $this->data['filter']]);
        $results =  $resultsRepository->findBy(['itemId' => $this->data['results'], 'filter' => $filter]);

        return [
            'filter' => $filter,
            'results' => $results,
        ];
    }
}