<?php

namespace App\modules\admin\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\Middleware\RoleMiddleware;
use App\services\CheckoutManager;
use App\services\RoleManager;
use App\services\UserManager;

#[RoleMiddleware("/api/errors/roles", "Admin")]
class ApiOrderController extends Controller
{
    public function __construct(private CheckoutManager $checkoutManager, private UserManager $userManager) {}

    #[HttpGet("/api/admin/orders")]
    public function getOrders(int $page = 1, int $limit = 1)
    {
        return $this->json(["code" => 200, "data" => $this->checkoutManager->getOrderWithPagination($page, $limit)]);
    }

    #[HttpGet("/api/admin/orders/prepare")]
    public function getPrepareOrder()
    {
        return $this->json(["code" => 200, "data" => $this->checkoutManager->getPrepareOrder()]);
    }

    // #[HttpPost("/api/admin/roles/create")]
    // public function createRole(CreateRoleModel $model)
    // {
    //     if ($model->isValid()) {
    //         $isError = false;
    //         if ($this->roleManager->hasName($model->name)) {
    //             $model->setError("name", "This role name has already existed!");
    //             $isError = true;
    //         }

    //         if (!$isError) {
    //             $this->roleManager->createRole($model->name);
    //             $role = $this->roleManager->findByName($model->name);
    //             return $this->json($role);
    //         }
    //     }
    //     return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    // }

    // #[HttpPost("/api/admin/roles/update")]
    // public function updateRole(UpdateRoleModel $model)
    // {
    //     if ($model->isValid()) {
    //         $isError = false;

    //         if (!$this->roleManager->hasId($model->id)) {
    //             $model->setError("id", "This role id is not found!");
    //             $isError = true;
    //         }

    //         if (!$this->roleManager->hasNameWithId($model->id, $model->name)) {
    //             $model->setError("name", "This role name has already existed!");
    //             $isError = true;
    //         }

    //         if (!$isError) {
    //             $this->roleManager->updateRole($model->id, $model->name);
    //             $role = $this->roleManager->findById($model->id);
    //             return $this->json($role);
    //         }
    //     }
    //     return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    // }

    // #[HttpPost("/api/admin/roles/delete")]
    // public function deleteRole(DeleteRoleModel $model)
    // {
    //     if ($model->isValid()) {
    //         $isError = false;

    //         if (!$this->roleManager->hasId($model->id)) {
    //             $model->setError("id", "This role id is not found!");
    //             $isError = true;
    //         }
    //         if ($this->userManager->hasRoleInUser($model->id)) {
    //             $model->setError("message", "There are users have this role!");
    //             $isError = true;
    //         }

    //         if (!$isError) {
    //             $role = $this->roleManager->findById($model->id);
    //             $this->roleManager->deleteRole($model->id);
    //             return $this->json($role);
    //         }
    //     }
    //     return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    // }
}
