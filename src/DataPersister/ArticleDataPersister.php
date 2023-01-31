<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Article;
use Psr\Log\InvalidArgumentException;
use Doctrine\ORM\EntityManagerInterface;

class ArticleDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Article;
    }

    public function persist($data, array $context = []): void
    {
        if (!empty($data->getStatus()) && $data->getStatus() != 'active') {
            throw new InvalidArgumentException("'status' should be 'active' or empty string");
        }

        if (!empty($data->getStatus())) {
            $data->setIsActive(1);
        }

        $data
            ->setCreatedAt($data->getCreatedAt())
            ->setPublishAt($data->getPublishAt())
            ->setTitle($data->getTitle())
            ->setContent($data->getContent());

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
