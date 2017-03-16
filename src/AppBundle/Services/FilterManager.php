<?php

namespace AppBundle\Services;

use AppBundle\Entity\Filter;
use AppBundle\Entity\Site;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Translation\TranslatorInterface;

class FilterManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * FilterManager constructor.
     * @param EntityManager $entityManager
     * @param TranslatorInterface $translator
     */
    public function __construct(EntityManager $entityManager, TranslatorInterface $translator)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
    }

    /**
     * @param User $user
     * @param string $url
     * @return Filter
     */
    public function addFilter(User $user, $url, $name)
    {
        $filter = new Filter();
        $filter->setUser($user);
        $filter->setUrl($url);
        $filter->setSite($this->parseUrl($url));
        $filter->setFilterName($name);
        $exist = $this->entityManager->getRepository('AppBundle:Filter')->findBy(['user' => $user, 'url' => $url]);
        if (count($exist) > 0) {
            throw new Exception($this->translator->trans('errors.duplicate_url'));
        }


        $this->entityManager->persist($filter);
        $this->entityManager->flush();

        return $filter;
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
            throw new \RuntimeException($this->translator->trans('errors.url_not_allowed'));
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