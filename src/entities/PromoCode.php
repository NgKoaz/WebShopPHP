<?php

namespace App\Entities;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity()]
#[Table("promo_codes")]
class PromoCode
{
    #[Id]
    #[Column(), GeneratedValue()]
    public int $id;

    #[Column(name: "discount_percent")]
    public int $discountPercent;

    #[Column(name: "discount_amount")]
    public int $discountAmount;

    #[Column(name: "discount_max")]
    public int $discountMax;
}
