<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

interface ApiLogProviderInterface
{
    public function __construct(string $apiName, string $requestUrl, string $requestHeaders, string $responseData, int $responseCode, EntityManagerInterface $manager);
}