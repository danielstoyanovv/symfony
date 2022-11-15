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
     * @ORM\Column(type="string", length=256)
     */
    private $customer_name;

    /**
     * @ORM\Column(type="string", length=256)
     */
    private $customer_email;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity=Song::class, inversedBy="ratingData")
     * @ORM\JoinColumn(nullable=false)
     */
    private $song;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * get name
     * @return string
     */
    public function getCustomerName(): string
    {
        return $this->customer_name;
    }

    /**
     * set name
     * @param string $customer_name
     * @return $this
     */
    public function setCustomerName(string $customer_name)
    {
        $this->customer_name = $customer_name;
        return $this;
    }

    /**
     * get name
     * @return string
     */
    public function getCustomerEmail(): string
    {
        return $this->customer_email;
    }

    /**
     * set name
     * @param string $customer_email
     * @return $this
     */
    public function setCustomerEmail(string $customer_email)
    {
        $this->customer_email = $customer_email;
        return $this;
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
}
