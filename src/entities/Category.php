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
#[Table("categories")]
class Category
{
    #[Id]
    #[Column, GeneratedValue]
    public int $id;

    #[Column(nullable: true)]
    public ?string $name;

    #[Column(nullable: true)]
    public ?string $slug;

    #[Column(name: "parent_id", nullable: true)]
    public ?int $parentId;

    #[OneToOne]
    #[JoinColumn(name: "parent_id", referencedColumnName: "id", nullable: false)]
    public Category $parentCategory;
}
