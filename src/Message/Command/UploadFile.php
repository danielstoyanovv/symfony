<?php

namespace App\Message\Command;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadFile
{
    /**
     * @var UploadedFile
     */
    private $file;

    /**
     * @var string
     */
    private $uploadDir;

    /**
     * @var string
     */
    private $uniqueFileName;

    public function __construct(UploadedFile $file, string $uploadDir, string $uniqueFileName) {
        $this->file = $file;
        $this->uploadDir = $uploadDir;
        $this->uniqueFileName = $uniqueFileName;
    }

    /**
     * @return UploadedFile
     */
    public function getFile(): UploadedFile
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getUploadDir(): string
    {
        return $this->uploadDir;
    }

    /**
     * @return string
     */
    public function getUniquerFileName(): string
    {
        return $this->uniqueFileName;
    }
}