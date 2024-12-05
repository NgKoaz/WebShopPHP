<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$viewData["title"] = "Overview";

$this
    ->addScript("Admin.js")
    ->addStylesheet("Admin.css");

ob_start();
?>

<div class="app-container">
    <div class="card-list">
        <div class="card"></div>
        <div class="card"></div>
        <div class="card"></div>
        <div class="card"></div>
    </div>
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Admin/AdminLayout.php";
?>