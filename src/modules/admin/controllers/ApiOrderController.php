<?php

namespace App\modules\admin\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\Middleware\RoleMiddleware;
use App\modules\admin\models\ApiCancelModel;
use App\services\CheckoutManager;
use App\services\UserManager;

#[RoleMiddleware("/api/errors/roles", "Admin")]
class ApiOrderController extends Controller
{
    public function __construct(private CheckoutManager $checkoutManager, private UserManager $userManager) {}

    #[HttpGet("/api/admin/orders")]
    public function getOrders(int $page = 1, int $limit = 12, ?string $id = null)
    {
        return $this->json(["code" => 200, "data" => $this->checkoutManager->getOrderWithPagination($page, $limit, $id)]);
    }

    #[HttpGet("/api/admin/orders/prepare")]
    public function getPrepareOrder()
    {
        return $this->json(["code" => 200, "data" => $this->checkoutManager->getPrepareOrder()]);
    }

    #[HttpPost("/api/admin/orders/cancel")]
    public function cancelOrder(ApiCancelModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if (!$this->checkoutManager->hasBill($model->billId)) {
                $model->setError("billId", "This bill id is not found!");
                $isError = true;
            }

            if (!$isError) {
                $this->checkoutManager->cancelBill($model->billId);
                return $this->json(["code" => 200, "message" => "Cancelled!"]);
            }
        }

        return $this->json(["code" => 400, "errors" => $model->getFullError()], 400);
    }

    #[HttpGet("/api/admin/orders/:id")]
    public function getOrder(string $id)
    {
        if (!$this->checkoutManager->hasBill($id))
            return $this->json(["code" => 400, "message" => "Id is not found!"], 400);

        $bill = $this->checkoutManager->findById($id);
        return $this->json(["code" => 200, "data" => $bill]);
    }

    #[HttpPost("/api/admin/orders/prepare")]
    public function prepareOrder(ApiCancelModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if (!$this->checkoutManager->hasBill($model->billId)) {
                $model->setError("billId", "This bill id is not found!");
                $isError = true;
            }

            if (!$isError) {
                $this->checkoutManager->onDonePrepare($model->billId);
                return $this->json(["code" => 200, "message" => "Ready to ship!"]);
            }
        }
        return $this->json(["code" => 400, "errors" => $model->getFullError()], 400);
    }


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
