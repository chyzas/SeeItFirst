<?php

namespace AppBundle\Services\Crawler;


class AruodasCrawler extends AbstractCrawler
{
    /**
     * @inheritdoc
     */
    public function getXpath()
    {
        return "//div[@class='number']";
    }
}