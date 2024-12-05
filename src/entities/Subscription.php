<?php

namespace App\Entities;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table("subscriptions")]
class Subscription
{
    #[Id]
    #[Column]
    public string $email;

    #[Column(name: "is_verified")]
    public bool $isVerified = false;

    #[Column(name: "sent_verify_email_at")]
    public ?DateTime $sentVerifyEmailAt;
}
