<?php

namespace AppBundle\Services;

use AppBundle\Entity\Filter;
use AppBundle\Entity\Site;
use AppBundle\Entity\User;
use AppBundle\Services\Queue\Queue;
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
     * @var Queue
     */
    private $queue;

    /**
     * FilterManager constructor.
     * @param EntityManager $entityManager
     * @param TranslatorInterface $translator
     * @param FilterValidatorService $urlValidatorService
     * @param Queue $queue
     */
    public function __construct(
        EntityManager $entityManager,
        TranslatorInterface $translator,
        FilterValidatorService $urlValidatorService,
        Queue $queue
    )
    {
        $this->entityManager = $entityManager;
        $this->translator = $translator;
        $this->urlValidatorService = $urlValidatorService;
        $this->queue = $queue;
    }

    /**
     * @param User $user
     * @param string $url
     */
    public function addFilter(User $user, $url, $name)
    {
        $currentFilters = $this->entityManager->getRepository('AppBundle:Filter')->findBy(['user' => $user]);
        //Todo this should be moved to validator service
        if (count($currentFilters) >= $user->getAvailableFilterCount()) {
            throw new Exception($this->translator->trans('errors.maximum_reached'));
        }
        $filter = new Filter();
        $filter->setUser($user);
        $site = $this->parseUrl($url);

        $url = $this->urlValidatorService->validateMobileUrl($site->getSiteUrl(), $url);

        $exist = $this->entityManager->getRepository('AppBundle:Filter')->findBy(['user' => $user, 'url' => $url]);
        if (count($exist) > 0) {
            throw new Exception($this->translator->trans('errors.duplicate_url'));
        }

//        $adsCount = $this->urlValidatorService->getAdsCount($site->getSiteUrl(), $url);
//
//        if ($adsCount > self::MAX_ADS_QTY) {
//            throw new \RuntimeException($this->translator->trans(
//                'errors.narrow_search',
//                [
//                    '%count%' => $adsCount,
//                    '%threshold%' => self::MAX_ADS_QTY,
//                ]
//            ));
//        }
        $filter->setSite($site);
        $filter->setUrl($url);
        $filter->setName($name);
        $token = $this->generateToken();
        $filter->setToken($token);
        $filter->setDeactivationToken($this->generateToken());

        $this->entityManager->persist($filter);
        $this->entityManager->flush();

        $this->queue->send(
            [
                'subject' => $this->translator->trans('email.filter_confirmation.subject', ['%name%' => $filter->getName()]),
                'email' => $user->getEmail(),
                'template' => 'confirm_filter',
                'data' => [
                    'token' => $token
                ]
            ]
        );
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

    /**
     * @return string
     */
    private function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
}
