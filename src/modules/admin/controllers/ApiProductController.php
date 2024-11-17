<?php

namespace App\modules\admin\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\modules\admin\models\CreateProductModel;
use App\modules\admin\models\DeleteProductModel;
use App\modules\admin\models\EditProductModel;
use App\services\ProductManager;

class ApiProductController extends Controller
{
    public function __construct(private ProductManager $productManager) {}

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

            if (!$isError) {
                $this->productManager->createProduct($model->name, $model->description, $model->price, $model->quantity, $model->slug);
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

            if (!$isError) {
                $this->productManager->editProduct($model->id, $model->name, $model->description, $model->price, $model->quantity, $model->slug, $model->isDeleted);
                $product = $this->productManager->getProductById($model->id);
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


    // #[HttpGet("/admin/products")]
    // public function getProducts()
    // {
    //     $this->view("Product");
    // }

    // #[HttpGet("/admin/categories")]
    // public function getCategories()
    // {
    //     $this->view("Category");
    // }

    // #[HttpGet("/admin/roles")]
    // public function getRoles()
    // {
    //     $this->view("Role");
    // }
}
