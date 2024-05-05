<?php

declare(strict_types=1);

namespace App\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

#[AsEventListener(event: KernelEvents::RESPONSE, method: 'onResponse')]
final readonly class FlushListener
{
    private const SUPPORTED_METHODS = ['POST', 'PUT', 'PUT', 'PATCH', 'DELETE'];

    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    public function onResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!in_array($request->getMethod(), self::SUPPORTED_METHODS, true)) {
            return;
        }

        $response = $event->getResponse();

        if (!$response->isSuccessful()) {
            return;
        }

        $this->em->flush();
    }
}
