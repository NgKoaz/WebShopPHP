<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$this
    ->addScript("Checkout.js")
    ->addStylesheet("Checkout.css");

ob_start();
?>

<div class="app-container">
    <input id="TempMessage" type="hidden" value="<?= $viewData["TempMessage"] ?>">
    <form method="POST" action="/checkout">
        <div class="input-group mb-3">
            <label>Email</label>
            <input class="text-input" type="email" disabled>
        </div>
        <div class="input-group mb-3">
            <label for="">Phone number</label>
            <input class="text-input" type="text" disabled>
        </div>
        <div class="input-group mb-3">
            <label for="">Payment method</label>
            <ul class="method-list">
                <li class="method">
                    <input type="radio" name="agency">
                    <span class="method-name">Momo</span>
                    <img src="/public/images/checkout/momo-48x48.png">
                </li>
            </ul>
        </div>
        <div class="input-group mb-3">
            <label for="">Address</label>
            <select>
                <option></option>
            </select>
        </div>
        <button class="btn btn-submit">Pay</button>
    </form>
</div>


<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/User/UserLayout.php";
?>