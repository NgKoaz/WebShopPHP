<?php

namespace App\modules\user\controllers;

use App\core\ArrayList;
use App\core\Attributes\Http\HttpGet;
use App\core\Controller;
use App\Middleware\Auth;
use App\services\CategoryManager;
use App\services\LoginManager;
use App\services\ProductManager;

class CategoryController extends Controller
{
    public function __construct(private LoginManager $loginManager, private ProductManager $productManager, private CategoryManager $categoryManager) {}

    #[HttpGet("/categories")]
    public function getShop(int $page = 1, int $limit = 6, string $query = "", string $options = "")
    {
        $viewData = new ArrayList;
        $viewData["blankPageUrl"] = $this->getBlankURI($page, $limit, $query, "", $options);;
        $viewData["IS_LOGGED_IN"] = $this->loginManager->isLoggedIn();

        $options = (strlen($options) > 0) ? json_decode($options, true) : [];
        $result = $this->productManager->getProductsComplex($page, $limit, $query, 0, $options);

        $viewData["products"] = $result["products"];
        $viewData["totalPages"] = $result["totalPages"];
        $viewData["currentPage"] = $result["currentPage"];
        $viewData["count"] = $result["count"];
        $viewData["from"] = $result["from"];
        $viewData["to"] = $result["to"];

        $this->view(viewData: $viewData);
    }

    #[HttpGet("/categories/:slug")]
    public function getProductByCategory(int $page = 1, int $limit = 6, string $query = "", string $slug = "", string $options = "")
    {
        $viewData = new ArrayList;
        $viewData["blankPageUrl"] = $this->getBlankURI($page, $limit, $query, $slug, $options);
        $viewData["IS_LOGGED_IN"] = $this->loginManager->isLoggedIn();

        if (strlen($slug) > 0) {
            $category = $this->categoryManager->findBySlug($slug);
            if ($category === null) return $this->loadSharedView("404");

            $options = (strlen($options) > 0) ? json_decode($options, true) : [];
            $result = $this->productManager->getProductsComplex($page, $limit, $query, $category->id, $options);

            $ancestorCategories = $this->categoryManager->getAncestors($category->id);
            $viewData["ancestorCategories"] = $ancestorCategories;

            $viewData["products"] = $result["products"];
            $viewData["totalPages"] = $result["totalPages"];
            $viewData["currentPage"] = $result["currentPage"];
            $viewData["count"] = $result["count"];
            $viewData["from"] = $result["from"];
            $viewData["to"] = $result["to"];
        }
        $this->view(viewData: $viewData);
    }

    private function getBlankURI(int $page = 1, int $limit = 6, string $query = "", string $slug = "", string $options = ""): string
    {
        $url = "/categories";
        if (strlen($slug) > 0)
            $url .= "/$slug";
        $url .= "?";
        $url .= "page=:page";
        if (strlen($query) > 0)
            $url .= "&query=$query";
        if (strlen($options) > 0)
            $url .= "&options=" . urlencode($options);

        return $url;
    }
}
