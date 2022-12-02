<?php

namespace App\Service;

use App\Entity\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploaderInterface
{
    /**
     * @param UploadedFile $file
     * @param string $projectDir
     * @param string|null $type
     * @return File|void
     */
    public function upload(UploadedFile $file, string $projectDir, string $type = null);

    /**
     * @param string $projectDir
     * @return string
     */
    public function getUploadsDir(string $projectDir): string;
}