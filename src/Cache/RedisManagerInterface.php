<?php

namespace App\Cache;

use Symfony\Component\Cache\Adapter\RedisTagAwareAdapter;

interface RedisManagerInterface
{
    /**
     * @return RedisTagAwareAdapter
     */
    public function getAdapter(): RedisTagAwareAdapter;
}