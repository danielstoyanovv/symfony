<?php

namespace App\Listeners;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LogoutListener implements EventSubscriberInterface
{
    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(
        CacheInterface $cache
    ) {
        $this->cache = $cache;
    }

    public function onLogout(LogoutEvent $event): void
    {
        $this->cache->clear('home_page');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogout',
        ];
    }
}