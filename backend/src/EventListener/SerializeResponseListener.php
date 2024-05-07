<?php

declare(strict_types=1);

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener(event: KernelEvents::VIEW, method: 'onKernelView')]
final readonly class SerializeResponseListener
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();

        if ($result instanceof Response) {
            return;
        }

        $request = $event->getRequest();

        $statusCode = match ($request->getMethod()) {
            'POST' => Response::HTTP_CREATED,
            'DELETE' => Response::HTTP_NO_CONTENT,
            default => Response::HTTP_OK,
        };

        $event->setResponse(JsonResponse::fromJsonString(
            $this->serializer->serialize($result, 'json'),
            $statusCode,
        ));
    }
}
