<?php

namespace App\modules\user\controllers;

use App\core\ArrayList;
use App\core\Attributes\Http\HttpGet;
use App\core\Controller;
use App\Middleware\Auth;
use App\services\LoginManager;
use App\services\ProductManager;

#[Auth("/login")]
class CategoryController extends Controller
{
    public function __construct(private LoginManager $loginManager, private ProductManager $productManager) {}

    #[HttpGet("/categories")]
    public function getShop(string $slug = "")
    {
        $viewData = new ArrayList;
        $viewData["IS_LOGGED_IN"] = $this->loginManager->isLoggedIn();
        if (strlen($slug) > 0) {
        }
        $this->view(viewData: $viewData);
    }

    #[HttpGet("/categories/:slug")]
    public function getIndex2(?string $slug)
    {
        $viewData = new ArrayList;
        $viewData["IS_LOGGED_IN"] = $this->loginManager->isLoggedIn();
        $this->view(viewData: $viewData);
    }
}
