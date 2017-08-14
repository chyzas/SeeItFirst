<?php

namespace AppBundle\Services\Crawler;


use GuzzleHttp\Client;
use GuzzleHttp\Handler\StreamHandler;
use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractCrawler
{
    abstract public function getXpath();

    /**
     * @param $url
     *
     * @return int
     */
    public function getCount($url)
    {
        $handler = new StreamHandler();
        $client = new Client(['handler' => $handler]);
        $html = $client->request('GET', $url)->getBody()->getContents();

        $crawler = new Crawler($html);

        $path = $crawler->filterXPath($this->getXpath())->html();

        return intval(preg_replace('/[^0-9]+/', '', $path), 10);
    }
}