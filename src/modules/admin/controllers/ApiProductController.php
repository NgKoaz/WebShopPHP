<?php

namespace App\modules\admin\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\modules\admin\models\CreateProductModel;
use App\modules\admin\models\DeleteProductModel;
use App\modules\admin\models\EditProductDetailsModel;
use App\modules\admin\models\EditProductModel;
use App\services\CategoryManager;
use App\services\ProductManager;

class ApiProductController extends Controller
{
    public function __construct(private ProductManager $productManager, private CategoryManager $categoryManager) {}

    #[HttpGet("/api/admin/products")]
    public function getProducts(int $page = 1, int $limit = 12)
    {
        return $this->json($this->productManager->getProductsWithNumPage($page, $limit));
    }

    #[HttpPost("/api/admin/products/create")]
    public function createProduct(CreateProductModel $model)
    {
        if ($model->isValid()) {
            $isError = false;
            if ($this->productManager->hasSlug($model->slug)) {
                $model->setError("slug", "Slug have already existed!");
                $isError = true;
            }

            if (!$this->categoryManager->hasId($model->categoryId)) {
                $model->setError("categoryId", "Category is not found!");
                $isError = true;
            }

            if (!$isError) {
                $this->productManager->createProduct($model->name, $model->description, $model->price, $model->quantity, $model->slug, $model->categoryId);
                $product = $this->productManager->getProductBySlug($model->slug);
                return $this->json($product);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/admin/products/edit")]
    public function editProduct(EditProductModel $model)
    {
        if ($model->isValid()) {
            $isError = false;
            if (!$this->productManager->hasId($model->id)) {
                $model->setError("id", "Product Id is not found!");
                $isError = true;
            }
            if (!$this->productManager->hasSlugWithId($model->id, $model->slug)) {
                $model->setError("slug", "Slug have already existed!");
                $isError = true;
            }
            if (!$this->categoryManager->hasId($model->categoryId)) {
                $model->setError("categoryId", "Category is not found!");
                $isError = true;
            }

            if (!$isError) {
                $this->productManager->editProduct($model->id, $model->name, $model->description, $model->price, $model->quantity, $model->slug, $model->isDeleted, $model->categoryId);
                $product = $this->productManager->findProductById($model->id);
                return $this->json($product);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/admin/products/delete")]
    public function deleteProduct(DeleteProductModel $model)
    {
        if ($model->isValid()) {
            $isError = false;
            if (!$this->productManager->hasId($model->id)) {
                $model->setError("id", "Product Id is not found!");
                $isError = true;
            }

            if (!$isError) {
                $this->productManager->deleteProduct($model->id);
                return $this->json($model->id);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }

    #[HttpGet("/api/admin/product")]
    public function getProduct(string $id = "", string $slug = "")
    {
        $isId = strlen($id) > 0;
        $isSlug = strlen($slug) > 0;

        if ($isId) {
            $product = $this->productManager->findProductById($id);
            if ($product === null) return $this->json(["code" => 404, "errors" => ["id" => ["Product id is not found!"]]], 400);
            return $this->json($product);
        }

        if ($isSlug) {
            $product = $this->productManager->getProductBySlug($slug);
            if ($product === null) return $this->json(["code" => 404, "errors" => ["slug" => ["Product slug is not found!"]]], 400);
            return $this->json($product);
        }

        return $this->json(["code" => 404, "errors" => ["id" => ["Need product id or product slug!"], "slug" => ["Need product id or product slug!"]]], 400);
    }

    #[HttpPost("/api/admin/products/detail/edit")]
    public function editProductDetails(EditProductDetailsModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if (!$this->productManager->hasId($model->id)) {
                $model->setError("id", "This product id is not found!");
                $isError = true;
            }

            if (!$isError) {
                $this->productManager->editProductDetails($model->id, $model->details);
                return $this->json([]);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }
}
