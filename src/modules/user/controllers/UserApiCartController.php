<?php

namespace App\modules\user\controllers;

use App\core\ArrayList;
use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\core\Util\ArrayHelper;
use App\modules\user\models\AddCartModel;
use App\modules\user\models\DeleteCartModel;
use App\modules\user\models\EditCartModel;
use App\services\CartManager;
use App\services\ProductManager;

class UserApiCartController extends Controller
{
    public function __construct(private CartManager $cartManager, private ProductManager $productManager) {}

    #[HttpGet("/api/cart")]
    public function getCart()
    {
        return $this->json($this->cartManager->getItems());
    }

    #[HttpPost("/api/cart")]
    public function addCart(AddCartModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if (!$this->productManager->hasId($model->productId)) {
                $model->setError("productId", "Product id is not found!");
                $isError = true;
            }

            $product = $this->productManager->findProductById($model->productId);
            if ($model->quantity > $product->quantity) {
                $model->setError("quantity", "There are(is)  $product->quantity item(s) left!");
                $isError = true;
            }

            if (!$isError) {
                $this->cartManager->addItem($model->productId, $model->quantity);
                return $this->json($this->cartManager->getItems());
            }
        }
        return $this->json(["code" => 400, "message" => $model->getSerializedErrorMessage()], 400);
    }

    #[HttpPost("/api/cart/edit")]
    public function editCart(EditCartModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if (!$this->productManager->hasId($model->productId)) {
                $model->setError("productId", "Product id is not found!");
                $isError = true;
            }

            if (!$isError) {
                $this->cartManager->setItem($model->productId, $model->quantity);
                return $this->json($this->cartManager->getItems());
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/cart/delete")]
    public function deleteCart(DeleteCartModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if (!$this->productManager->hasId($model->productId)) {
                $model->setError("productId", "Product id is not found!");
                $isError = true;
            }

            if (!$isError) {
                $this->cartManager->unsetItem($model->productId);
                return $this->json($this->cartManager->getItems());
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/cart/checkout")]
    public function checkoutCart()
    {
        $cartItems = $this->cartManager->getItems2();
        $isOutOfStock = (count($cartItems) > 0) ? ArrayHelper::some($cartItems, fn($item) => $item["product"]["quantity"] < $item["quantity"]) : false;

        return $isOutOfStock ?
            $this->json(["code" => 400, "message" => "Some item is out of stock! Check again!"], 400) :
            $this->json(["code" => 200, "redirect" => "/checkout"]);
    }
}
