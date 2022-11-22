<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;

interface SendEmailInterface
{
    /**
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $templateName
     * @param array|null $templateVars
     * @param File|null $file
     * @return void
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function send(string $from, string $to, string $subject, string $templateName, array $templateVars = null, File $file = null): void;
}