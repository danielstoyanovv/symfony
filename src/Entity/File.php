<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={
            "get",
 *          "put"
 *     },
 *     normalizationContext={"groups"={"file:read"}},
 *     denormalizationContext={"groups"={"file:write"}}
 * )
 * @ORM\Entity(repositoryClass=FileRepository::class)
 */
class File
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
     * @Assert\NotBlank()
     * @Groups({"file:read", "file:write", "product:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"file:read", "file:write", "product:read"})
     */
    private $mime;

    /**
     * @ORM\Column(type="float")
     * @Groups({"file:read", "file:write", "product:read"})
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"file:read", "file:write", "product:read"})
     */
    private $originalName;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="image")
     * @Groups({"file:read", "product:write", "product:read"})
     */
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"file:read", "file:write", "product:read"})
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMime(): ?string
    {
        return $this->mime;
    }

    public function setMime(string $mime): self
    {
        $this->mime = $mime;

        return $this;
    }

    public function getSize(): ?float
    {
        return $this->size;
    }

    public function setSize(float $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): self
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
