<?php

namespace App\Listeners;

use App\Cache\RedisManagerInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\EventListener\SessionLogoutListener;

class LogoutListener extends SessionLogoutListener
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
        parent::onLogout($event);
    }
}