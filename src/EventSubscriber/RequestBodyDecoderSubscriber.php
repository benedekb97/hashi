<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestBodyDecoderSubscriber implements EventSubscriberInterface
{
    public function onKernelEvent(ControllerEvent $event)
    {
        $request = $event->getRequest();

        if (0 === strpos($request->headers->get('Content-Type') ?? '', 'application/json')) {
            $requestData = json_decode($request->getcontent(), true);

            $request->request->replace(is_array($requestData) ? $requestData : null);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelEvent'
        ];
    }
}