<?php

namespace App\Entity;

use App\Repository\RatingDataRepository;
use Doctrine\ORM\Mapping as ORM;
use \App\Entity\Song;

/**
 * @ORM\Entity(repositoryClass=RatingDataRepository::class)
 * @ORM\Table(name="rating_data")
 */
class RatingData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity=Song::class, inversedBy="ratingData")
     * @ORM\JoinColumn(nullable=false)
     */
    private $song;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ratingData")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * get name
     * @return string
     */
    public function getRating(): string
    {
        return $this->rating;
    }

    /**
     * set name
     * @param string $rating
     * @return $this
     */
    public function setRating(string $rating)
    {
        $this->rating = $rating;
        return $this;
    }

    public function getSong(): ?Song
    {
        return $this->song;
    }

    public function setSong(?Song $song): self
    {
        $this->song = $song;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
