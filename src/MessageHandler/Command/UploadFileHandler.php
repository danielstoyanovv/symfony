<?php

namespace App\MessageHandler\Command;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Message\Command\UploadFile;

class UploadFileHandler implements MessageHandlerInterface
{
    public function __invoke(UploadFile $uploadFile)
    {
        $file = $uploadFile->getFile();

        $uploadedFile = $file->move(
            $uploadFile->getUploadDir(),
            $uploadFile->getUniquerFileName()
        );
    }
}