<?php

namespace App\services;

use App\core\App;

class CartManager
{
    private static string $CART = "CART";

    public function __construct(private SessionManager $sessionManager, private ProductManager $productManager) {}

    public function addItem(mixed $productId, int $quantity = 1): void
    {
        $cartItems = $this->sessionManager->getEntry(CartManager::$CART);
        if (isset($cartItems[$productId])) {
            $cartItems[$productId] += 1;
            return;
        }
        $this->sessionManager->setEntry(CartManager::$CART, $productId, $quantity);
    }

    public function getItems(): array
    {
        $cartItems = $this->sessionManager->getEntry(CartManager::$CART)->array();
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
        return $result;
    }
}
