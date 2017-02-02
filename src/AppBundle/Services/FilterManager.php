<?php

namespace AppBundle\Services;

use AppBundle\Entity\Filter;
use AppBundle\Entity\Site;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class FilterManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * FilterManager constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @param string $url
     */
    public function addFilter(User $user, $url, $name)
    {
        $filter = new Filter();
        $filter->setUser($user);
        $filter->setUrl($url);
        $filter->setSite($this->parseUrl($url));
        $filter->setFilterName($name);
        $this->entityManager->persist($filter);
        $this->entityManager->flush();
    }


    /**
     * @param $str
     * @return Site
     */
    public function parseUrl($str)
    {
        $host = $this->get_domain($str);
        /** @var Site $site */
        $site = $this->entityManager->getRepository('AppBundle:Site')->findOneBy(['siteUrl' => $host]);
        if (!$site) {
            throw new \RuntimeException('Sorry, this website is not allowed.');
        }

        return $site;
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