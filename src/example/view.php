<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$this
    ->addScript("index.js")
    ->addStylesheet("index.css");

ob_start();
?>

<h1>DAY LA TEMPLATE</h1>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/layout.php";
?>