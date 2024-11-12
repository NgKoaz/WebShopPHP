<?php

namespace App\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: "user_claims")]
class UserClaim
{
    #[Id]
    #[Column, GeneratedValue]
    public int $id;

    #[Column(name: "claim_type")]
    public string $claimType;

    #[Column(name: "claim_value")]
    public string $claimValue;

    #[Column(name: "user_id")]
    public int $userId;

    #[OneToOne(inversedBy: "userClaim")]
    #[JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    public User $user;
}
