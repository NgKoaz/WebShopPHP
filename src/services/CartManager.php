<?php

namespace App\services;

use App\core\App;
use App\core\Util\ArrayHelper;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\EntityManager;

class CartManager
{
    private static string $CART = "CART";

    public function __construct(
        private EntityManager $entityManager,
        private SessionManager $sessionManager,
        private ProductManager $productManager
    ) {}

    public function addItem(mixed $productId, int $quantity = 1): void
    {
        $cartItems = $this->sessionManager->getEntry(CartManager::$CART);
        if (isset($cartItems[$productId])) {
            $this->sessionManager->setInEntry(CartManager::$CART, $productId, $cartItems[$productId] + $quantity);
            return;
        }
        $this->sessionManager->setInEntry(CartManager::$CART, $productId, $quantity);
    }

    public function setItem(mixed $productId, int $quantity): void
    {
        $this->sessionManager->setInEntry(CartManager::$CART, $productId, $quantity);
    }

    public function unsetItem(mixed $productId): void
    {
        $cartItems = $this->sessionManager->unsetInEntry(CartManager::$CART, $productId);
    }

    public function clear(): void
    {
        $this->sessionManager->unsetEntry(CartManager::$CART);
    }

    public function getItems(): array
    {
        $cartItems = $this->sessionManager->getEntry(CartManager::$CART);
        $result = [];
        foreach ($cartItems as $productId => $quantity) {
            $product = $this->productManager->findProductById($productId);
            if ($product === null) {
                $this->sessionManager->unsetInEntry(CartManager::$CART, $productId);
                continue;
            }

            array_push($result, [
                "product" => $product,
                "quantity" => $quantity
            ]);
        }
        return $result ?? [];
    }

    public function getItems2(): array
    {
        $cartItems = $this->sessionManager->getEntry(CartManager::$CART);
        $productIds = ArrayHelper::mapKeyValue($cartItems, fn($productId, $quantity) => $productId);
        if (count($productIds) === 0) return [];

        $sql = "
            SELECT p.id, p.name, p.price, p.quantity, p.slug, p.images, p.sold_number
            FROM products p
            WHERE p.id IN (" . join(",", $productIds) . ");
        ";
        $query = $this->entityManager->getConnection()->prepare($sql);
        $stmt = $query->executeQuery();
        $products = $stmt->fetchAllAssociative();

        $result = ArrayHelper::mapKeyValue($cartItems, function ($productId, $quantity) use ($products) {
            return [
                "product" => ArrayHelper::findOne($products, fn($product) => $product["id"] == $productId),
                "quantity" => $quantity
            ];
        });

        return $result;
    }


    public function getTotalPrice(): float
    {
        $result = 0;
        $items = $this->getItems();
        foreach ($items as $item) {
            $result += $item["product"]->price * $item["quantity"];
        }
        return $result;
    }
}
