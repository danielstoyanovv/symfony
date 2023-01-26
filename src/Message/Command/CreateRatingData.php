<?php

namespace App\Message\Command;

class CreateRatingData
{
    /**
     * @var integer
     */
    private $songId;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $rating;

    public function __construct(int $songId, int $userId, int $rating)
    {
        $this->songId = $songId;
        $this->userId = $userId;
        $this->rating = $rating;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getSongId(): int
    {
        return $this->songId;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }
}