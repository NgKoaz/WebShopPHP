<?php

class HomeController extends Controller
{

    public function __construct()
    {
        echo "Lm hcua di xong di toi uu";
        $this->getIndex();
    }

    public static function registerRoutes($router)
    {
        $router->get("/", "HomeController@index");
    }

    public function getIndex()
    {
        $this->view("home/index");
    }
}
