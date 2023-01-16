<?php

namespace App\Cache;

use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;

class RedisManager implements RedisManagerInterface
{

    /**
     * @return RedisTagAwareAdapter
     */
    public function getAdapter(): RedisTagAwareAdapter
    {
        $cacheClient = RedisAdapter::createConnection('redis://localhost:6379');
        return new RedisTagAwareAdapter($cacheClient);
    }
}