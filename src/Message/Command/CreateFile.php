<?php

namespace App\Message\Command;

class CreateFile
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $size;

    /**
     * @var string
     */
    private $mime;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $uniqueFileName;

    public function __construct(string $type, string $size, string $mime, string $fileName, string $uniqueFileName)
    {
        $this->type = $type;
        $this->size = $size;
        $this->mime = $mime;
        $this->fileName = $fileName;
        $this->uniqueFileName = $uniqueFileName;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMime(): string
    {
        return $this->mime;
    }

    /**
     * @return string
     */
    public function getSize(): string
    {
        return $this->size;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getUniqueFileName(): string
    {
        return $this->uniqueFileName;
    }
}