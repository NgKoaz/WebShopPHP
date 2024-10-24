<?php

class Controller
{
    public function view($viewPath, $viewData = [])
    {
        $viewPath = trim($viewPath, "/");
        require_once "/phppractice/src/views/$viewPath.php";
    }
}
