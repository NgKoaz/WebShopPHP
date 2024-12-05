<?php

namespace App\services;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;



class MailService
{
    public PHPMailer $mailer;

    public function __construct(string $host, string $username, string $password, string $port, string $nameDisplay)
    {
        $this->mailer = new PHPMailer();
        $this->mailer->isSMTP();

        $this->mailer->Host = $host;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username =  $username;
        $this->mailer->Password = $password;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = $port;

        $this->mailer->setFrom('khoaprovips5@gmail.com', $nameDisplay);
    }


    public function send(array $recipients, string $subject, string $body, bool $isHtml = true): bool
    {
        foreach ($recipients as $recipient)
            $this->mailer->addAddress($recipient);

        $this->mailer->isHTML($isHtml);
        $this->mailer->Subject = $subject;
        $this->mailer->Body    = $body;

        try {
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
