<?php

namespace App\Service;

use App\Message\Command\CreateFile;
use App\Message\Command\UploadFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\MessageBusInterface;

class Uploader implements UploaderInterface
{
    /**
     * @param UploadedFile $file
     * @param string $projectDir
     * @param string $type
     * @param MessageBusInterface $messageBus
     * @return string
     */
    public function upload(UploadedFile $file, string $projectDir, string $type, MessageBusInterface $messageBus): string
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
        $uniqueFileName = $fileName . '-' . uniqid() . '.' . $file->guessExtension();
        $size = $file->getSize();
        $mime = $file->getMimeType();

        $messageBus->dispatch(new UploadFile($file, $uploadDir, $uniqueFileName));

        $messageBus->dispatch(new CreateFile($type, $size, $mime, $fileName, $uniqueFileName));

        return $uniqueFileName;
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