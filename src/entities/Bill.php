<?php

namespace App\Entities;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table("bills")]
class Bill
{
    #[Id]
    #[Column]
    public string $id;

    #[Column]
    public string $status;

    #[Column(name: "pay_method", nullable: true)]
    public ?string $payMethod = null;

    #[Column(name: "payment_service_provider", nullable: true)]
    public ?string $paymentServiceProvider = null;

    #[Column(name: "total_price", precision: 2)]
    public string $totalPrice;

    #[Column(name: "order_id")]
    public int $orderId;

    #[Column(name: "user_id")]
    public int $userId;

    #[Column(name: "created_at", nullable: true)]
    public ?DateTime $createdAt;

    #[Column(name: "canceled_at", nullable: true)]
    public ?DateTime $canceledAt = null;

    #[Column(name: "paid_at", nullable: true)]
    public ?DateTime $paidAt = null;

    #[OneToOne(cascade: ["persist"])]
    #[JoinColumn(name: "order_id", referencedColumnName: "id", nullable: false)]
    public Order $order;

    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(name: "user_id", referencedColumnName: "id", nullable: false)]
    public User $user;
}
