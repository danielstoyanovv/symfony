<?php

namespace App\Entity;

use App\Repository\ApiTokenRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     itemOperations={
 *          "get"
 *     },
 *      collectionOperations={
 *          "post"
 *     },
 *     normalizationContext={"groups"={"api_token:read"}},
 *     denormalizationContext={"groups"={"api_token:write"}}
 * )
 * @ORM\Entity(repositoryClass=ApiTokenRepository::class)
 */
class ApiToken
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"api_token:read"})
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"api_token:read"})
     */
    private $expiresAt;

    /**
     * @var string
     * @Groups({"api_token:write"})
     */
    public $email;

    /**
     * @var string
     * @Groups({"api_token:write"})
     */
    public $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }
}
