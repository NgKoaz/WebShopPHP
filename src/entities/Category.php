<?php

namespace App\Entities;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
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

    #[OneToMany(targetEntity: Product::class, mappedBy: "category")]
    public Collection $products;

    #[Column(name: "promotion_id", nullable: true)]
    public ?int $promotionId;

    #[ManyToOne(targetEntity: Promotion::class, inversedBy: "categories")]
    #[JoinColumn(name: "promotion_id", referencedColumnName: "id", nullable: true)]
    public Promotion $promotion;

    #[ManyToOne(targetEntity: Category::class, inversedBy: "subcategories")]
    #[JoinColumn(name: "parent_id", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
    public ?Category $parentCategory = null;

    #[OneToMany(mappedBy: "parentCategory", targetEntity: Category::class)]
    public Collection $subcategories;
}
