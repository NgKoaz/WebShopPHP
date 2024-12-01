<?php

namespace App\Entities;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\TransactionRequiredException;

#[Entity]
#[Table("users")]
class User
{
    #[Id]
    #[Column, GeneratedValue]
    public int $id;

    #[Column(unique: true)]
    public string $username;

    #[Column(unique: true)]
    public string $email;

    #[Column(name: "password_hash")]
    public string $passwordHash;

    #[Column(name: "phone_number")]
    public string $phoneNumber;

    #[Column(name: "first_name")]
    public string $firstName;

    #[Column(name: "last_name")]
    public string $lastName;

    #[Column(name: "address", nullable: true)]
    public string $address;

    #[Column(nullable: true)]
    public string $roles;

    #[Column(name: "is_deleted")]
    public bool $isDeleted = false;

    #[Column(name: "used_promo_codes", nullable: true)]
    public string $usedPromoCodes;

    #[Column(name: "is_verified_email")]
    public bool $isVerifiedEmail = false;

    #[Column(name: "deleted_at",  type: "datetime", nullable: True)]
    public DateTime $deleteAt;

    #[Column(name: "created_at", type: 'datetime')]
    public DateTime $createdAt;

    #[OneToOne(targetEntity: UserClaim::class, mappedBy: "user")]
    public UserClaim $userClaim;

    #[OneToOne(targetEntity: UserLogin::class, mappedBy: "user")]
    public UserLogin $userLogin;

    #[OneToOne(targetEntity: UserToken::class, mappedBy: "user")]
    public UserLogin $userToken;
}
