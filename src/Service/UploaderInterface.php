<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Messenger\MessageBusInterface;

interface UploaderInterface
{
    /**
     * @param UploadedFile $file
     * @param string $projectDir
     * @param string $type
     * @param MessageBusInterface $messageBus
     * @return string
     */
    public function upload(UploadedFile $file, string $projectDir, string $type, MessageBusInterface $messageBus): string;

    /**
     * @param string $projectDir
     * @return string
     */
    public function getUploadsDir(string $projectDir): string;
}