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

    #[Column]
    public string $name;

    #[Column]
    public string $slug;

    #[OneToOne]
    #[JoinColumn(name: "parent_category_id", referencedColumnName: "id", nullable: false)]
    public Category $parentCategory;
}
