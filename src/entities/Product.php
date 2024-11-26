<?php

namespace App\Entities;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table("products")]
class Product
{
    #[Id]
    #[Column(), GeneratedValue]
    public int $id;

    #[Column()]
    public string $name;

    #[Column()]
    public string $description;

    #[Column(type: "decimal", precision: 10, scale: 2)]
    public string $price;

    #[Column()]
    public int $quantity;

    #[Column(nullable: false)]
    public int $rate;

    #[Column]
    public string $slug;

    #[Column(nullable: true)]
    public ?string $details;

    #[Column(nullable: true)]
    public ?string $images;

    #[Column(name: "category_id", nullable: true)]
    public int $categoryId;

    #[ManyToOne(targetEntity: Category::class, inversedBy: "products")]
    #[JoinColumn(name: "category_id", referencedColumnName: "id", nullable: true)]
    public Category $category;

    // #[OneToMany(targetEntity: Review::class, mappedBy: "product")]
    // public Collection $reviews;

    #[Column(name: "sold_number", nullable: false)]
    public int $soldNumber;

    #[Column(name: "is_deleted")]
    public bool $isDeleted;

    #[Column(name: "deleted_at", type: "datetime", nullable: true)]
    public DateTime $deletedAt;

    #[Column(name: "created_at", type: "datetime")]
    public DateTime $createdAt;

    #[Column(name: "updated_at", type: "datetime")]
    public DateTime $updatedAt;
}
