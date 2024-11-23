<?php

namespace App\modules\admin\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\modules\admin\models\CreateUserModel;
use App\modules\admin\models\DeleteUserModel;
use App\modules\admin\models\EditUserModel;
use App\services\RoleManager;
use App\services\UserManager;

class ApiUserController extends Controller
{
    public function __construct(private UserManager $userManager, private RoleManager $roleManager) {}

    #[HttpGet("/api/admin/users")]
    public function getUsers(int $page = 1, int $limit = 12)
    {
        $result = $this->userManager->getUsersWithNumPage($page, $limit);
        $result["roles"] = $this->roleManager->getRoles();
        return $this->json($result);
    }

    #[HttpPost("/api/admin/users/create")]
    public function createUser(CreateUserModel $model)
    {
        if ($model->isValid()) {
            $isError = false;
            if ($this->userManager->hasUsername($model->username)) {
                $model->setError("username", "This Username [$model->username] have already existed!");
                $isError = true;
            } else if ($this->userManager->hasEmail($model->username)) {
                $model->setError("username", "This Username [$model->username] have already existed!");
                $isError = true;
            }
            if ($this->userManager->hasEmail($model->email)) {
                $model->setError("email", "This Email [$model->username] have already existed!");
                $isError = true;
            }

            if (!$this->roleManager->hasRoles($model->roles)) {
                $model->setError("roles", "Some role is not exist!");
                $isError = true;
            }

            if (!$isError) {
                $this->userManager->register(
                    $model->firstname,
                    $model->lastname,
                    $model->username,
                    $model->email,
                    $model->phone,
                    $model->password,
                    $model->roles
                );
                $user = $this->userManager->findByUsername($model->username);
                return $this->json($user);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/admin/users/edit")]
    public function editUser(EditUserModel $model)
    {
        if ($model->isValid()) {
            $isError = false;
            if (!$this->userManager->hasId($model->id)) {
                $model->setError("id", "This id $model->id is not found!");
                $isError = true;
            }

            if (!$this->roleManager->hasRoles($model->roles)) {
                $model->setError("roles", "Some role is not exist!");
                $isError = true;
            }

            if (!$isError) {
                $this->userManager->editUserById(
                    $model->id,
                    $model->firstname,
                    $model->lastname,
                    $model->phone,
                    $model->isDeleted,
                    $model->password,
                    $model->roles
                );
                $user = $this->userManager->findById($model->id);
                return $this->json($user);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }

    #[HttpPost("/api/admin/users/delete")]
    public function deleteUser(DeleteUserModel $model)
    {
        if ($model->isValid()) {
            $isError = false;
            if (!$this->userManager->hasId($model->id)) {
                $model->setError("id", "This id $model->id is not found!");
                $isError = true;
            }

            if (!$isError) {
                $this->userManager->deleteUserById($model->id);
                return $this->json($model->id);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }
}
