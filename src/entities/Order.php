<?php

namespace App\Entities;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table("orders")]
class Order
{
    #[Id]
    #[Column, GeneratedValue]
    public int $id;

    #[Column]
    public string $status;

    #[Column]
    public string $products;

    #[Column(name: "canceled_at", type: "datetime")]
    public DateTime $canceledAt;

    #[Column(name: "created_at", type: "datetime")]
    public DateTime $createdAt;
}
