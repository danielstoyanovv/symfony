<?php

namespace App\Listeners;

use App\Cache\RedisManagerInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LogoutListener implements EventSubscriberInterface
{
    /**
     * @var RedisManagerInterface
     */
    private $redisManager;

    public function __construct(
        RedisManagerInterface $redisManager
    ) {
        $this->redisManager = $redisManager;
    }

    public function onLogout(LogoutEvent $event): void
    {
        $cache = $this->redisManager->getAdapter();
        $cache->clear('home_page');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogout',
        ];
    }
}