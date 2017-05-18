<?php

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

    /**
     * @param $url
     *
     * @return array
     */
    public function validateMobileUrl($url)
    {
        $pieces = parse_url($url);

        if (substr($pieces['host'], 0,2) === 'm.') {
            $pieces['host'] = substr($pieces['host'], 2);

            return [$pieces['scheme'] . '://www.' . $pieces['host'] . $pieces['path'] . '?'. $pieces['query'], $pieces['host']];
        }

        return [$url, $pieces['host']];
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