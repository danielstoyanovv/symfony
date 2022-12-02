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
     * @param string|null $type
     * @return File|void
     */
    public function upload(UploadedFile $file, string $projectDir, string $type = null)
    {
        $uploadDir = $this->getUploadsDir($projectDir);
        switch ($type) {
            case 'file':
                $uploadDir .= 'files' . DIRECTORY_SEPARATOR;
                break;
            default:
                $uploadDir .= 'images' . DIRECTORY_SEPARATOR;
        }


        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $uploadedFile = $file->move(
            $uploadDir,
            $fileName . '-' . uniqid() . '.' . $file->guessExtension()
        );


        $newFile = new File();
        $newFile
            ->setOriginalName($fileName)
            ->setMime($uploadedFile->getMimeType())
            ->setSize($uploadedFile->getSize())
            ->setName($fileName . '-' . uniqid() . '.' . $uploadedFile->guessExtension());

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