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

    public function __invoke(CreateFile $createFile)
    {
        $newFile = new File();
        $newFile
            ->setType($createFile->getType())
            ->setOriginalName($createFile->getUniqueFileName())
            ->setMime($createFile->getMime())
            ->setSize($createFile->getSize())
            ->setName($createFile->getUniqueFileName());

        $this->entityManager->persist($newFile);
        $this->entityManager->flush();
    }
}