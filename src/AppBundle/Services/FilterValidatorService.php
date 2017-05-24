<?php

namespace AppBundle\Services;

use AppBundle\Entity\Site;
use AppBundle\Services\Crawler\AruodasCrawler;
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
            case Site::SITE_ARUODAS:
                return (new AruodasCrawler())->getCount($url);
            default:
                throw new \RuntimeException('Something went wrong');
        }
    }

    /**
     * @param $url
     * @param $host
     *
     * @return string
     */
    public function validateMobileUrl($host, $url)
    {
        $pieces = parse_url($url);

        if (isset($pieces['query'])) {
            return $pieces['scheme'] . '://www.' . $host . $pieces['path'] . '?'. $pieces['query'];
        }

        return $pieces['scheme'] . '://www.' . $host . $pieces['path'];
    }

    /**
     * @param string $url
     * @return bool
     */
    function get_domain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {

            return $regs['domain'];
        }

        return false;
    }
}