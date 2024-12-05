<?php

use App\core\App;

$title = "Your Cart";
$this
    ->addScript("index.js")
    ->addStylesheet("index.css");

ob_start();
?>

<div class="app-container">
    <div class="breadcrumb">
        <a href="/">Home</a> >
        <a href="/cart">Cart</a>
    </div>

    <h1 class="main-title">Your Cart</h1>

    <div class="main-content">
        <b class="no-item-notify">No any items in your cart.</b>
    </div>
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/User/UserLayout.php";
?>