<?php

namespace App\modules\user\controllers;

use App\core\ArrayList;
use App\core\Attributes\Http\HttpGet;
use App\core\Controller;
use App\Middleware\Auth;
use App\services\CategoryManager;
use App\services\LoginManager;
use App\services\ProductManager;

#[Auth("/login")]
class CategoryController extends Controller
{
    public function __construct(private LoginManager $loginManager, private ProductManager $productManager, private CategoryManager $categoryManager) {}

    #[HttpGet("/categories")]
    public function getShop()
    {
        $viewData = new ArrayList;
        $viewData["IS_LOGGED_IN"] = $this->loginManager->isLoggedIn();
        $this->view(viewData: $viewData);
    }

    #[HttpGet("/categories/:slug")]
    public function getProductByCategory(?string $slug)
    {
        // echo "HELLO";
        // var_dump($slug);
        $viewData = new ArrayList;
        $viewData["IS_LOGGED_IN"] = $this->loginManager->isLoggedIn();
        if (strlen($slug) > 0) {
            $category = $this->categoryManager->findBySlug($slug);

            if ($category === null) return $this->loadSharedView("404");

            $products = $this->productManager->findAllByCategoryId($category->id);
            $ancestorCategories = $this->categoryManager->getAncestors($category->id);
            $viewData["ancestorCategories"] = $ancestorCategories;
            $viewData["products"] = $products;
        }
        $this->view(viewData: $viewData);
    }
}
