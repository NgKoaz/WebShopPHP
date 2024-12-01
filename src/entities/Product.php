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

    #[Column(type: "text")]
    public string $description;

    #[Column(type: "decimal", precision: 10, scale: 2)]
    public string $price;

    #[Column()]
    public int $quantity;

    #[Column(nullable: false)]
    public int $rate;

    #[Column]
    public string $slug;

    #[Column(nullable: true, type: "text")]
    public ?string $details;

    #[Column(nullable: true, type: "text")]
    public ?string $images;

    #[Column(name: "category_id", nullable: true)]
    public int $categoryId;

    #[Column(name: "total_rates")]
    public int $totalRates;

    #[Column(name: "total_reviews")]
    public int $totalReviews;

    #[Column(name: "promotion_id", nullable: true)]
    public int $promotionId;

    #[ManyToOne(targetEntity: Promotion::class, inversedBy: "products")]
    #[JoinColumn(name: "promotion_id", referencedColumnName: "id", nullable: true)]
    public Promotion $promotion;

    #[ManyToOne(targetEntity: Category::class, inversedBy: "products")]
    #[JoinColumn(name: "category_id", referencedColumnName: "id", nullable: true)]
    public Category $category;

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
