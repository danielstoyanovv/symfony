<?php

namespace App\Service;

use App\Factory\ApiLogFactory;
use Doctrine\ORM\EntityManagerInterface;

class ApiLogProvider implements ApiLogProviderInterface
{
    /**
     * @param string $apiName
     * @param string $requestUrl
     * @param string $requestHeaders
     * @param string $responseData
     * @param int $responseCode
     * @param EntityManagerInterface $manager
     */
    public function __construct(string $apiName, string $requestUrl, string $requestHeaders, string $responseData, int $responseCode, EntityManagerInterface $manager) {
        ApiLogFactory::createOne([
            'apiName' => $apiName,
            'requestUrl' => $requestUrl,
            'requestHeaders' => $requestHeaders,
            'responseData' => $responseData,
            'responseCode' => $responseCode
        ]);

        $manager->flush();
    }
}