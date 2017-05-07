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
    const MAX_ADS_QTY = 200;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var FilterValidatorService
     */
    private $urlValidatorService;

    /**
     * FilterManager constructor.
     * @param EntityManager $entityManager
     * @param TranslatorInterface $translator
     * @param FilterValidatorService $urlValidatorService
     */
    public function __construct(EntityManager $entityManager, TranslatorInterface $translator, FilterValidatorService $urlValidatorService)
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->urlValidatorService = $urlValidatorService;
    }

    /**
     * @param User $user
     * @param string $url
     * @return Filter
     */
    public function addFilter(User $user, $url, $name)
    {
        $currentFilters = $this->entityManager->getRepository('AppBundle:Filter')->findBy(['user' => $user]);
        //Todo this should be moved to validator service
        if (count($currentFilters) >= 3) {
            throw new Exception($this->translator->trans('errors.maximum_reached'));
        }
        $filter = new Filter();
        $filter->setUser($user);
        $filter->setUrl($url);
        $filter->setSite($this->parseUrl($url));
        $filter->setFilterName($name);
        $exist = $this->entityManager->getRepository('AppBundle:Filter')->findBy(['user' => $user, 'url' => $url]);
        //Todo this should be moved to validator service
        if (count($exist) > 0) {
            throw new Exception($this->translator->trans('errors.duplicate_url'));
        }

        $this->entityManager->persist($filter);
        $this->entityManager->flush();

        return $filter;
    }

    /**
     * @param $url
     * @return Site
     */
    public function parseUrl($url)
    {
        $host = $this->get_domain($url);
        /** @var Site $site */
        $site = $this->entityManager->getRepository('AppBundle:Site')->findOneBy(['siteUrl' => $host]);
        if (!$site) {
            throw new \RuntimeException($this->translator->trans('errors.url_not_allowed'));
        }

        $adsCount = $this->urlValidatorService->getAdsCount($host, $url);

        if ($adsCount > self::MAX_ADS_QTY) {
            throw new \RuntimeException($this->translator->trans(
                'errors.narrow_search',
                [
                    '%count%' => $adsCount,
                    '%threshold%' => self::MAX_ADS_QTY,
                ]
            ));
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