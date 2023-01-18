<?php

namespace App\Service;

interface TokenGeneratorInterface
{
    /**
     * @return string
     */
    public function generate(): string;
}