<?php
/**
 * Created by PhpStorm.
 * User: mantas
 * Date: 17.9.26
 * Time: 20.47
 */

namespace AppBundle\Services;


class DefaultDataManager implements DataManagerInterface
{
    /**
     * @var
     */
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}