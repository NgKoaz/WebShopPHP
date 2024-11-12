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
#[Table("bills")]
class Bill
{
    #[Id]
    #[Column, GeneratedValue]
    public int $id;

    #[Column]
    public string $status;

    #[Column]
    public string $payMethod;

    #[Column(name: "payment_service_provider")]
    public string $paymentServiceProvider;

    #[Column(name: "total_price")]
    public int $totalPrice;

    #[OneToOne]
    #[JoinColumn(name: "order_id", referencedColumnName: "id", nullable: false)]
    public Order $order;
}
