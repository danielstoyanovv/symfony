<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     itemOperations={
*           "get",
*           "put" = {"security" = "is_granted('ROLE_USER')"},
*           "delete" = {"security" = "is_granted('ROLE_USER')"},
*           "patch" = {"security" = "is_granted('ROLE_USER')"}
 *     },
 *      collectionOperations={
 *          "get",
 *          "post" = {"security" = "is_granted('ROLE_USER')"}
 *     },
 *     normalizationContext={"groups"={"product:read"}},
 *     denormalizationContext={"groups"={"product:write"}}
 * )
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=4,
     *     max=200,
     *     maxMessage="Maxumum allowed length for name is: 200 characters."
     * )
     * @Groups({"product:read", "product:write", "file:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"product:read", "product:write", "file:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"product:read", "product:write", "file:read"})
     */
    private $price;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"product:read", "product:write", "file:read"})
     */
    private $position;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"product:read", "file:read"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=File::class, mappedBy="product", cascade={"persist"})
     * @Groups({"product:read", "product:write", "file:write"})
     */
    private $image;

    public function __construct()
    {
        $this->image = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, File>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(File $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(File $image): self
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }
}
