<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$this
    ->addScript("Admin.js")
    ->addStylesheet("Admin.css");

ob_start();
?>

<h1>DAY LA TEMPLATE ADMIn123</h1>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Admin/AdminLayout.php";
?>