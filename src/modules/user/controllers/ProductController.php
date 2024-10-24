<?php

class ProductController extends Controller
{
    public static function registerRoutes($router, $module)
    {
        ProductController::$module = $module;

        $controllerName = "ProductController";
        $router->get("/product", "$controllerName@getIndex");
    }

    public function getIndex()
    {
        $this->view(ProductController::$module, "product", "index");
    }
}
