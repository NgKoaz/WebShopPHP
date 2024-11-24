<?php

namespace App\modules\user\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Controller;
use App\services\CategoryManager;
use App\services\LoginManager;
use App\services\ProductManager;

// #[Auth("/login")]
class UserApiProductController extends Controller
{
    public function __construct(private LoginManager $loginManager, private ProductManager $productManager, private CategoryManager $categoryManager) {}

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
    public function getProducts(int $page = 1, int $limit = 12, string $query = "", string $slug = "", string $options = "")
    {
        if (strlen($slug) > 0) {
            $category = $this->categoryManager->findBySlug($slug);
            if ($category === null) return $this->loadSharedView("404");
            return $this->json(["code" => 404, "errors" => ["message" => "Category slug is not found!"]], 400);
        }

        $options = (strlen($options) > 0) ? json_decode($options, true) : [];
        $result = $this->productManager->getProductsComplex($page, $limit, $query, $slug, $options);

        return $this->json($result);
    }
}
