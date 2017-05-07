<?php
/**
 * Created by PhpStorm.
 * User: Chyzas
 * Date: 5/7/2017
 * Time: 3:33 PM
 */

namespace AppBundle\Services;


use AppBundle\Entity\Site;
use AppBundle\Services\Crawler\AutopliusCrawler;
use AppBundle\Services\Crawler\SkelbiuCrawler;

class FilterValidatorService
{
    /**
     * @param $host
     * @param $url
     * @return int
     */
    public function getAdsCount($host, $url)
    {
        switch ($host) {
            case Site::SITE_SKELBIU:
                return (new SkelbiuCrawler())->getCount($url);
            case Site::SITE_AUTOPLIUS:
                return (new AutopliusCrawler())->getCount($url);
            default:
                throw new \RuntimeException('Something went wrong');
        }
    }
}