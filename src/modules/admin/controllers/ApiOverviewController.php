<?php

namespace App\modules\admin\controllers;

use App\core\Controller;
use App\core\Attributes\Http\HttpGet;
use App\Middleware\RoleMiddleware;
use App\services\CategoryManager;
use App\services\CheckoutManager;
use App\services\UserManager;

#[RoleMiddleware("/api/errors/roles", "Admin")]
class ApiOverviewController extends Controller
{
    public function __construct(
        private CategoryManager $categoryManager,
        private UserManager $userManager,
        private CheckoutManager $checkoutManager,
    ) {}

    #[HttpGet("/api/admin/overview/users")]
    public function getOverviewUsers()
    {
        $data = $this->userManager->getOverviewUsers();
        return $this->json(["code" => 200, "data" => $data]);
    }

    #[HttpGet("/api/admin/overview/orders")]
    public function getOverviewOrders()
    {
        $data = $this->checkoutManager->getOverviewOrders();
        return $this->json(["code" => 200, "data" => $data]);
    }
}
