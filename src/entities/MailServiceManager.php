<?php

namespace App\Entities;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table("mail_service_manager")]
class MailServiceManager
{
    #[Id]
    #[Column]
    public string $email;

    #[Column(name: "sent_verify_email_at")]
    public ?DateTime $sentAt;
}
