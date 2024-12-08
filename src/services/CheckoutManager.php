<?php

namespace App\services;

use App\core\Util\ArrayHelper;
use App\Entities\Bill;
use App\Entities\Order;
use DateTime;
use Doctrine\ORM\EntityManager;
use PDO;

define("ORDER_PREPARING", "PREPARING");
define("ORDER_SHIPPING", "SHIPPING");
define("ORDER_SHIPPED", "SHIPPED");
define("ORDER_RECEIVED", "RECEIVED");
define("ORDER_CANCELLED", "CANCELLED");


define("BILL_UNPAID", "UNPAID");
define("BILL_PAID", "PAID");
define("BILL_CANCELLED", "CANCELLED");


define("ONLINE_METHOD", "ONLINE");
define("OFFLINE_METHOD", "OFFLINE");

define("MOMO_PSP", "MOMO");
// define("MOMO_PSP", "MOMO");

class CheckoutManager
{
    public function __construct(
        private CartManager $cartManager,
        private LoginManager $loginManager,
        private EntityManager $entityManager,
        private ProductManager $productManager,
        private SessionManager $sessionManager
    ) {}

    public function createOrderForCurrentUser(): ?Bill
    {
        $orderProducts = [];
        $items = $this->cartManager->getItems();
        $totalPrice = 0;
        foreach ($items as $item) {
            $product = $item["product"];
            $quantity = $item["quantity"];
            $orderProducts[] = [
                "id" => $product->id,
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => $quantity,
                "image" => ArrayHelper::firstOrDefault(json_decode($product->images, true), null)
            ];
            $totalPrice += $quantity * $product->price;
        }

        if ($totalPrice === 0) return null;

        $order = new Order;
        $order->status = ORDER_PREPARING;
        $order->products = json_encode($orderProducts);

        $bill = new Bill;
        $bill->id = time() . uniqid();
        $bill->status = BILL_UNPAID;
        $bill->totalPrice = $totalPrice;
        $bill->order = $order;
        $user = $this->loginManager->getCurrentUser();
        $bill->user = $user;
        $bill->createdAt = new DateTime;

        $this->entityManager->persist($bill);
        $this->entityManager->flush();

        return $bill;
    }

    public function deleteByObject(Bill $bill): void
    {
        $this->entityManager->remove($bill);
        $this->entityManager->flush();
    }

    public function deleteById(string $id): void
    {
        $bill = $this->findById($id);
        if ($bill !== null) {
            $this->deleteByObject($bill);
        }
    }

    public function findById(string $id): ?Bill
    {
        return $this->entityManager->getRepository(Bill::class)->findOneBy(["id" => $id]);
    }

    public function findByIdAssociate(string $id): array
    {
        $connection = $this->entityManager->getConnection();
        $stmt = $connection->prepare("
            SELECT b.*, o.status as order_status, o.products
            FROM bills b 
            INNER JOIN orders o ON o.id = b.order_id
            WHERE b.id = :id
        ");

        $stmt->bindValue("id", $id);
        $result = $stmt->executeQuery()->fetchAllAssociative();
        return $result;
    }

    public function onPaidBill(string $billId, string $payMethod, string $paymentServiceProvider)
    {
        $bill = $this->findById($billId);
        $bill->status = BILL_PAID;
        $bill->paidAt = new DateTime;
        $bill->payMethod = $payMethod;
        $bill->paymentServiceProvider = $paymentServiceProvider;
        $this->entityManager->flush();
    }

    public function findBillByUserId(int $userId)
    {
        $connection = $this->entityManager->getConnection();
        $stmt = $connection->prepare("
            SELECT b.*, o.status as order_status, o.products
            FROM bills b 
            INNER JOIN orders o ON o.id = b.order_id
            WHERE b.user_id = :userId
        ");

        $stmt->bindValue("userId", $userId);;
        $result = $stmt->executeQuery()->fetchAllAssociative();

        return $result;
    }

    public function hasBill(string $id): bool
    {
        return $this->findById($id) !== null;
    }

    public function cancelBill(string $id): void
    {
        $bill = $this->findById($id);
        $bill->canceledAt = new DateTime;
        $bill->status = BILL_CANCELLED;
        $bill->order->status = ORDER_CANCELLED;
        $this->entityManager->flush();
    }

    public function rebuy(string $billId): void
    {
        $srcBill = $this->findById($billId);
        $items = json_decode($srcBill->order->products, true);
        foreach ($items as $item) {
            $productId = $item["id"];
            $quantity = $item["quantity"];
            $this->cartManager->addItem($productId, $quantity);
        }
    }

    public function getOrderWithPagination(int $page, int $limit, ?string $id)
    {
        $queryBuilder = $this->entityManager
            ->getRepository(Bill::class)
            ->createQueryBuilder('b')
            ->select('COUNT(b.id)');
        if ($id !== null) {
            $queryBuilder
                ->andWhere('b.id = :id')
                ->setParameter('id', $id);
        }
        $count = $queryBuilder->getQuery()->getSingleScalarResult();



        $totalPages = ceil($count / $limit);
        $page = ($page < 1) ? 1 : $page;
        $offset = ($page - 1) * $limit;
        $offset = $offset < 0 ? 0 : $offset;

        $sql = '
            SELECT b.*, o.status as order_status, o.products
            FROM bills b
            JOIN orders o ON b.order_id = o.id ';

        if ($id !== null) $sql .= " WHERE b.id = '$id' ";

        $sql .= 'LIMIT ' . intval($limit) . '
            OFFSET ' . intval($offset);

        $query = $this->entityManager->getConnection()->prepare($sql);
        $stmt = $query->executeQuery();
        $orders = $stmt->fetchAllAssociative();
        // var_dump($orders);
        return [
            "orders" => $orders,
            "totalPages" => $totalPages,
            "currentPage" => $page
        ];
    }

    public function getPrepareOrder(): array
    {
        $sql = "
            SELECT b.*, o.status as order_status, o.products
            FROM bills b
            JOIN orders o ON b.order_id = o.id
            WHERE o.status = '" . ORDER_PREPARING . "'
        ";

        $orderQuery = $this->entityManager->getConnection()->prepare($sql);
        $stmt = $orderQuery->executeQuery();
        $orders = $stmt->fetchAllAssociative();

        return $orders;
    }

    public function onDonePrepare(string $billId): void
    {
        $bill = $this->findById($billId);
        if ($bill->order->status !== ORDER_PREPARING) return;
        $bill->order->status = ORDER_SHIPPED;
        $this->entityManager->flush();
    }
}
