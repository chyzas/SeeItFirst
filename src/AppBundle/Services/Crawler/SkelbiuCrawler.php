<?php

namespace AppBundle\Services\Crawler;


class SkelbiuCrawler extends AbstractCrawler
{
    /**
     * @return string
     */
    public function getXpath()
    {
        return '//*[@id="adsNumberFilterBar"]';
    }
}