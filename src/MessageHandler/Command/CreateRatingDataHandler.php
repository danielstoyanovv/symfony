<?php

namespace App\MessageHandler\Command;

use App\Entity\RatingData;
use App\Entity\Song;
use App\Entity\User;
use App\Message\Command\CreateRatingData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateRatingDataHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface  $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(CreateRatingData $createRatingData)
    {
        if ($user = $this->entityManager->getRepository(User::class)->find($createRatingData->getUserId())) {
            if ($song = $this->entityManager->getRepository(Song::class)->find($createRatingData->getSongId())) {
                $rating = new RatingData();
                $rating->setSong($song);
                $rating->setUser($user);
                $rating->setRating($createRatingData->getRating());
                $this->entityManager->persist($rating);
                $this->entityManager->flush();
            }
        }
    }
}