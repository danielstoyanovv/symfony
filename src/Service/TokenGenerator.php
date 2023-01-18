<?php

namespace App\Service;

class TokenGenerator implements TokenGeneratorInterface
{
    /**
     * @return string
     */
    public function generate(): string
    {
        return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }
}