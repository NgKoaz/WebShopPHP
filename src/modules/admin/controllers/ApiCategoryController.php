<?php

namespace App\modules\admin\controllers;

use App\core\Controller;
use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\Middleware\RoleMiddleware;
use App\modules\admin\models\CreateCategoryModel;
use App\modules\admin\models\DeleteCategoryModel;
use App\modules\admin\models\UpdateCategoryModel;
use App\services\CategoryManager;

#[RoleMiddleware("/api/errors/roles", "Admin")]
class ApiCategoryController extends Controller
{
    public function __construct(private CategoryManager $categoryManager) {}

    #[HttpGet("/api/admin/categories")]
    public function getCategories()
    {
        return $this->json($this->categoryManager->getCategories());
    }

    #[HttpPost("/api/admin/categories/create")]
    public function createCategory(CreateCategoryModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if ($this->categoryManager->hasSlug($model->slug)) {
                $model->setError("slug", "Slug have already existed!");
                $isError = true;
            }

            if ($model->parentId !== null && $this->categoryManager->hasId($model->parentId) === null) {
                $model->setError("id", "Parent id is not exist!");
                $isError = true;
            }

            if (!$isError) {
                $this->categoryManager->createCategory($model->name, $model->slug, $model->parentId);
                $category = $this->categoryManager->findBySlug($model->slug);
                return $this->json($category);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }


    #[HttpPost("/api/admin/categories/update")]
    public function updateCategory(UpdateCategoryModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if (!$this->categoryManager->hasId($model->id)) {
                $model->setError("id", "Category id is not found!");
                $isError = true;
            }

            if (!$this->categoryManager->hasSlugWithId($model->id, $model->slug)) {
                $model->setError("slug", "Slug have already existed!");
                $isError = true;
            }

            if ($model->parentId !== null && $this->categoryManager->hasId($model->parentId) === null) {
                $model->setError("id", "Parent id is not exist!");
                $isError = true;
            }

            if (!$isError) {
                $this->categoryManager->updateCategory($model->id, $model->name, $model->slug, $model->parentId);
                $category = $this->categoryManager->findById($model->id);
                return $this->json($category);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }


    #[HttpPost("/api/admin/categories/delete")]
    public function deleteCategory(DeleteCategoryModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if (!$this->categoryManager->hasId($model->id)) {
                $model->setError("id", "Category id is not found!");
                $isError = true;
            }

            if (!$isError) {
                $category = $this->categoryManager->findById($model->id);
                $this->categoryManager->deleteCategory($model->id);
                return $this->json($category);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }
}
