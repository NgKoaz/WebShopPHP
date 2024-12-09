<?php

use App\core\App;
use App\core\Container;
use App\services\LoginManager;

$title = "Bach Khoa Clothes";
$this
    ->addScript("Checkout.js")
    ->addStylesheet("Checkout.css");

$container = Container::getInstance();
$loginManager = $container->create(LoginManager::class);


$user = $loginManager->getCurrentUser();
$isUserExists = $user !== null;


ob_start();
?>

<div class="app-container">
    <p class="paragraph">If you want to fix your information, please click at the <i>Profile icon</i> on navigation, right after the <i>Cart icon</i> to change infomation</p>
    <form method="POST" action="/checkout">
        <div class="input-group mb-3">
            <label>Email</label>
            <input class="text-input" type="email" value="<?= ($isUserExists) ? $user->email : "" ?>" disabled>
        </div>
        <div class="input-group mb-3">
            <label for="">Phone number</label>
            <input class="text-input" type="text" value="<?= ($isUserExists) ? $user->phoneNumber : "" ?>" disabled>
        </div>
        <div class="input-group mb-3">
            <label for="">Payment method</label>
            <ul class="method-list">
                <li class="method">
                    <input type="radio" name="method" value="MOMO">
                    <span class="method-name">Momo</span>
                    <img src="/public/images/checkout/momo-48x48.png">
                </li>
            </ul>
        </div>
        <div class="input-group mb-3">
            <label for="">Address</label>
            <input class="text-input" type="text" value="<?= ($isUserExists && isset($user->address)) ? $user->address : "" ?>" disabled>
        </div>
        <button class="btn btn-submit">Pay</button>
    </form>
</div>


<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/User/UserLayout.php";
?>