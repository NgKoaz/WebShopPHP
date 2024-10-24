<?php

class CartController extends Controller
{
    public static function registerRoutes($router, $module)
    {
        CartController::$module = $module;
        $router->get("/cart", "CartController@getIndex");
    }

    public function getIndex()
    {
        $this->view(CartController::$module, "cart", "index");
    }
}
