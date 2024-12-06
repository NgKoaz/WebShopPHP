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
    <div class="info-container">
        <div class="card">

        </div>
    </div>
    <div class="order-container">
        <div class="order">
            sofos
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Admin/AdminLayout.php";
?>