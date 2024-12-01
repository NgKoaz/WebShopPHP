<?php

namespace App\Entities;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity()]
#[Table("promotions")]
class Promotion
{
    #[Id]
    #[Column(), GeneratedValue()]
    public int $id;

    #[Column(name: "discount_percent", nullable: true)]
    public int $discountPercent;

    #[Column(name: "discount_amount", nullable: true)]
    public int $discountAmount;

    #[Column(name: "discount_max", nullable: true)]
    public int $discountMax;

    #[OneToMany(targetEntity: Product::class, mappedBy: "promotionId")]
    public Collection $products;

    #[OneToMany(targetEntity: Category::class, mappedBy: "promotionId")]
    public Collection $categories;
}
