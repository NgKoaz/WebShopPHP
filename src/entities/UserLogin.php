<?php

namespace App\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table("user_logins")]
class UserLogin
{
    #[Id]
    #[Column(name: "login_provider")]
    public string $loginProvider;

    #[Id]
    #[Column(name: "provider_key")]
    public string $providerKey;

    #[Column(name: "provider_display_name")]
    public string $providerDisplayName;

    #[Column(name: "user_id")]
    public int $userId;

    #[OneToOne(targetEntity: User::class, inversedBy: "userLogin")]
    #[JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    public User $user;
}
