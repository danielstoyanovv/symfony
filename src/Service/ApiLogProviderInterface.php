<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

interface ApiLogProviderInterface
{
    /**
     * @param string $apiName
     * @param string $requestUrl
     * @param string $requestHeaders
     * @param string $responseData
     * @param int $responseCode
     * @param EntityManagerInterface $manager
     * @return void
     */
    public function createLog(string $apiName, string $requestUrl, string $requestHeaders, string $responseData, int $responseCode, EntityManagerInterface $manager): void;
}