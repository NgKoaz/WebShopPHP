<?php

namespace App\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table("role_claims")]
class RoleClaim
{
    #[Id]
    #[Column, GeneratedValue()]
    public int $id;

    #[Column(name: "role_id")]
    public int $roleId;

    #[Column(name: "claim_name")]
    public string $claimName;

    #[Column(name: "claim_value")]
    public string $claimValue;

    #[ManyToOne(inversedBy: "roleClaims")]
    public Role $role;
}
