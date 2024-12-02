<?php

namespace App\modules\admin\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Controller;
use App\Middleware\RoleMiddleware;

#[RoleMiddleware("/", "Admin")]
class AdminController extends Controller
{
    public function __construct() {}

    #[HttpGet("/admin")]
    public function getAdmin()
    {
        $this->view("Admin");
    }

    #[HttpGet("/admin/users")]
    public function getUsers()
    {
        $this->view("User");
    }

    #[HttpGet("/admin/products")]
    public function getProducts()
    {
        $this->view("Product");
    }

    #[HttpGet("/admin/categories")]
    public function getCategories()
    {
        $this->view("Category");
    }

    #[HttpGet("/admin/roles")]
    public function getRoles()
    {
        $this->view("Role");
    }
}
