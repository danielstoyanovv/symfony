<?php


namespace App\Service;

use App\Entity\ApiLog;

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
        $apiLog = new ApiLog();
        $apiLog
            ->setApiName($apiName)
            ->setRequestUrl($requestUrl)
            ->setRequestHeaders($requestHeaders)
            ->setResponseData($responseData)
            ->setResponseCode($responseCode);

        $manager->persist($apiLog);
        $manager->flush();
    }
}