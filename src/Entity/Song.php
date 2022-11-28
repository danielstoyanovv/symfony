<?php

namespace App\Entity;

use App\Repository\SongRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=SongRepository::class)
 * @ORM\Table(name="song")
 */
class Song
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=256, unique=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=RatingData::class, mappedBy="song", orphanRemoval=true)
     */
    private $ratingData;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $author;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    public function __construct()
    {
        $this->ratingData = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * get name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * set name
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, RatingData>
     */
    public function getRatingData(): Collection
    {
        return $this->ratingData;
    }

    public function addRatingData(RatingData $ratingData): self
    {
        if (!$this->ratingData->contains($ratingData)) {
            $this->ratingData[] = $ratingData;
            $ratingData->setSong($this);
        }

        return $this;
    }

    public function removeRatingData(RatingData $ratingData): self
    {
        if ($this->ratingData->removeElement($ratingData)) {
            // set the owning side to null (unless already changed)
            if ($ratingData->getSong() === $this) {
                $ratingData->setSong(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): self
    {
        $this->author = $author;

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
}
