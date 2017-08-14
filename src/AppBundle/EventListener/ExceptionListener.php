<?php

namespace AppBundle\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Translation\Translator;

class ExceptionListener
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(LoggerInterface $logger, Translator $translator)
    {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $this->logger->error($exception->getMessage());

        return new JsonResponse([
            'message' => $this->translator->trans('errors.bad_request')
        ], Response::HTTP_BAD_REQUEST
        );
    }
}