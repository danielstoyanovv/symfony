<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\File;
use Doctrine\ORM\EntityManagerInterface;

class Uploader implements UploaderInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param UploadedFile $file
     * @param string $projectDir
     * @param string $type
     * @return File|void
     */
    public function upload(UploadedFile $file, string $projectDir, string $type)
    {
        $uploadDir = $this->getUploadsDir($projectDir);
        switch ($type) {
            case 'file':
                $uploadDir .= 'files' . DIRECTORY_SEPARATOR;
                break;
            case 'image':
                $uploadDir .= 'images' . DIRECTORY_SEPARATOR;
        }

        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $uniquerFileName = $fileName . '-' . uniqid() . '.' . $file->guessExtension();
        $uploadedFile = $file->move(
            $uploadDir,
            $uniquerFileName
        );

        $newFile = new File();
        $newFile
            ->setType($type)
            ->setOriginalName($fileName)
            ->setMime($uploadedFile->getMimeType())
            ->setSize($uploadedFile->getSize())
            ->setName($uniquerFileName);

        $this->entityManager->persist($newFile);
        $this->entityManager->flush();

        return $newFile;
    }

    /**
     * @param string $projectDir
     * @return string
     */
    public function getUploadsDir(string $projectDir): string
    {
        return $projectDir . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads'. DIRECTORY_SEPARATOR;
    }


}