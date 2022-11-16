<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\File\File;

class SendEmail
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

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
    public function sendWithTemplate(string $from, string $to, string $subject, string $templateName, array $templateVars = null, File $file = null)
    {
        $email = (new TemplatedEmail())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate('email' . DIRECTORY_SEPARATOR . $templateName . '.html.twig');
        if (!empty($templateVars)) {
            $email->context($templateVars);
        }
        if (!empty($file)) {
            $email->attachFromPath($file->getRealPath());
        }
        $this->mailer->send($email);
    }
}