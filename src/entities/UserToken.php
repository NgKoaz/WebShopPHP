<?php

namespace App\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table("user_tokens")]
class UserToken
{
    #[Id]
    #[Column(name: "user_id")]
    public int $userId;

    #[OneToOne]
    #[JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    public User $user;

    #[Id]
    #[Column(name: "login_provider")]
    public string $loginProvider;

    #[Column]
    public string $name;

    #[Column]
    public string $value;
}
