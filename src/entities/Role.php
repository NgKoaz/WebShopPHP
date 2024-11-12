<?php

namespace App\Entities;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table("roles")]
class Role
{
    #[Id]
    #[Column, GeneratedValue]
    public int $id;

    #[Column]
    public string $name;

    #[OneToMany(targetEntity: RoleClaim::class, mappedBy: "role")]
    public Collection $roleClaims;
}
