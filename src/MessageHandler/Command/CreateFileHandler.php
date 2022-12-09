<?php

namespace App\MessageHandler\Command;

use App\Entity\File;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Message\Command\CreateFile;
use Doctrine\ORM\EntityManagerInterface;

class CreateFileHandler implements MessageHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function __invoke(CreateFile $uploadFile)
    {
        $newFile = new File();
        $newFile
            ->setType($uploadFile->getType())
            ->setOriginalName($uploadFile->getUniqueFileName())
            ->setMime($uploadFile->getMime())
            ->setSize($uploadFile->getSize())
            ->setName($uploadFile->getUniqueFileName());

        $this->entityManager->persist($newFile);
        $this->entityManager->flush();
    }
}