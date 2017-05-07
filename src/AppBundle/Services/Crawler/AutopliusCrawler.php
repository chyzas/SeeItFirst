<?php

namespace AppBundle\Services\Crawler;

class AutopliusCrawler extends AbstractCrawler
{
    public function getXpath()
    {
        return "//span[contains(@class, 'filter-count')]";
    }
}