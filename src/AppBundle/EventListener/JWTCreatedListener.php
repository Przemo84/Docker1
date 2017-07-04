<?php

namespace AppBundle\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();

        $now = new \DateTime('now');
        $now = $now->getTimestamp();
        $expiration = $now+7200;

        $payload = $event->getData();

        $payload['ip'] = $request->getClientIp();
        $payload['locale'] = $request->getDefaultLocale();
        $payload['host'] = $request->getHost();
        $payload['exp'] = $expiration;

        $event->setData($payload);
    }


}