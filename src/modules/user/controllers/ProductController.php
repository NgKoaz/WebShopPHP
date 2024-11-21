<?php

namespace App\modules\user\controllers;

use App\core\ArrayList;
use App\core\Attributes\Http\HttpGet;
use App\core\Controller;
use App\Middleware\Auth;
use App\services\LoginManager;
use App\services\ProductManager;

class ProductController extends Controller
{
    public function __construct(private LoginManager $loginManager, private ProductManager $productManager) {}

    #[HttpGet("/products/:slug")]
    public function getDetail(string $slug)
    {
        $viewData = new ArrayList;
        $viewData["IS_LOGGED_IN"] = $this->loginManager->isLoggedIn();
        $product = $this->productManager->getProductBySlug($slug);
        if ($product === null) return $this->loadSharedView("404");
        $viewData["product"] = $product;
        return $this->view(viewData: $viewData);
    }
}
