<?php

namespace App\Entity;

use App\Repository\ApiLogRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=ApiLogRepository::class)
 */
class ApiLog
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $apiName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $requestUrl;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $requestHeaders;

    /**
     * @ORM\Column(type="text")
     */
    private $responseData;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $responseCode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApiName(): ?string
    {
        return $this->apiName;
    }

    public function setApiName(string $apiName): self
    {
        $this->apiName = $apiName;

        return $this;
    }

    public function getRequestUrl(): ?string
    {
        return $this->requestUrl;
    }

    public function setRequestUrl(?string $requestUrl): self
    {
        $this->requestUrl = $requestUrl;

        return $this;
    }

    public function getRequestHeaders(): ?string
    {
        return $this->requestHeaders;
    }

    public function setRequestHeaders(?string $requestHeaders): self
    {
        $this->requestHeaders = $requestHeaders;

        return $this;
    }

    public function getResponseData(): ?string
    {
        return $this->responseData;
    }

    public function setResponseData(string $responseData): self
    {
        $this->responseData = $responseData;

        return $this;
    }

    public function getResponseCode(): ?int
    {
        return $this->responseCode;
    }

    public function setResponseCode(?int $responseCode): self
    {
        $this->responseCode = $responseCode;

        return $this;
    }
}
