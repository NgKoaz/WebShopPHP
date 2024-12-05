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

    #[Column(type: "text")]
    public string $products;
}
