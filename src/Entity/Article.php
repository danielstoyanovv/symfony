<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *      itemOperations={
 *           "get",
 *     },
 *     attributes={
            "pagination_items_per_page"=10
 *     },
 *     normalizationContext={"groups"={"article:read"}},
 *     denormalizationContext={"groups"={"article:write"}}
 * )
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @ApiFilter(SearchFilter::class, properties={"status": "partial"})
 * @ApiFilter(DateFilter::class, properties={"createdAt"})
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"article:read", "article:write"})
     * @Assert\Type("\DateTimeInterface")
     * @var \DateTime()
     */
    public $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"article:read", "article:write"})
     * @Assert\Type("\DateTimeInterface")
     * @var \DateTime()
     */
    public $publishAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"article:read", "article:write"})
     * @Assert\NotBlank()
     */
    public $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"article:read", "article:write"})
     */
    public $content;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"article:read", "article:write"})
     */
    public $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->createdAt = $created_at;

        return $this;
    }

    public function getPublishAt(): ?\DateTimeInterface
    {
        return $this->publishAt;
    }

    public function setPublishAt(?\DateTimeInterface $publish_at): self
    {
        $this->publishAt = $publish_at;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
