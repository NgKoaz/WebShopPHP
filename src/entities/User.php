<?php

namespace App\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table("users")]
class User
{
    #[Id]
    #[Column]
    public string $id;

    #[Column]
    public string $username;

    #[Column(nullable: true)]
    public string $email;

    #[Column(name: "password_hash")]
    public string $passwordHash;

    #[Column]
    public string $salt;

    #[Column(name: "phone_number", nullable: true)]
    public string $phoneNumber;
}
