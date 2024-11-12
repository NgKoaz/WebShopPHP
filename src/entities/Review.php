<?php

namespace App\Entities;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table("reviews")]
class Review
{
    #[Id]
    #[Column, GeneratedValue]
    public int $id;

    #[Column]
    public string $comment;

    #[Column]
    public int $rate;

    #[Column(name: "user_id")]
    public int $userId;

    #[ManyToOne]
    #[JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    public User $user;

    #[ManyToOne(inversedBy: "reviews")]
    #[JoinColumn(name: "product_id", referencedColumnName: "id", nullable: false)]
    public Product $product;

    #[Column(name: "is_deleted")]
    public bool $isDeleted;

    #[Column(name: "deleted_at", type: 'datetime')]
    public DateTime $deletedAt;

    #[Column(name: "updated_at", type: 'datetime')]
    public DateTime $updatedAt;

    #[Column(name: "created_at", type: 'datetime')]
    public DateTime $createdAt;
}
