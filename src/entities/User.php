<?php

namespace App\Entities;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToOne;
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

    #[Column(name: "first_name")]
    public string $firstName;

    #[Column(name: "last_name")]
    public string $lastName;

    #[Column(name: "address")]
    public string $address;

    #[Column(name: "is_deleted")]
    public bool $isDeleted;

    #[Column(name: "deleted_at",  type: "datetime", nullable: True)]
    public DateTime $deleteAt;

    #[Column(name: "created_at", type: 'datetime')]
    public DateTime $createdAt;

    #[OneToOne(mappedBy: "userClaim")]
    public User $user;

    #[OneToOne(mappedBy: "user")]
    public UserLogin $userLogin;
}
