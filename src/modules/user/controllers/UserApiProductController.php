<?php

namespace App\modules\user\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Controller;
use App\services\LoginManager;
use App\services\ProductManager;

// #[Auth("/login")]
class UserApiProductController extends Controller
{
    public function __construct(private LoginManager $loginManager, private ProductManager $productManager) {}

    #[HttpGet("/api/search")]
    public function getDetail(string $name = "")
    {
        $products = $this->productManager->findProductsByName($name);
        $count = count($products);
        $moreNum = 0;
        if ($count > 3) {
            $products = array_slice($products, 0, 3);
            $moreNum = $count - 3;
        }
        return $this->json([
            "products" => $products,
            "moreNum" => $moreNum
        ]);
    }

    #[HttpGet("/api/products")]
    public function getProducts(int $page = 1, int $limit = 12, string $q = "",  string $options = "")
    {
        $jsonOptions = json_decode(urldecode($options), true) ?? [];
        return $this->json($this->productManager->getProductsComplex($page, $limit, $q, $jsonOptions));
    }
}
