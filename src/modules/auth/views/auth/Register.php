<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$this
    ->addScript("Register.js")
    ->addStylesheet("Register.css");

ob_start();
?>

<div class="register-container">
    <h4 class="main-title mb-4">Welcome a new customer</h4>
    <div class="register-form">
        <form method="POST" action="/register">
            <div class="composite-input mb-4">
                <div class="w-50">
                    <label>First name</label>
                    <input type="text" class="input" placeholder="First name" name="firstName"
                        value="<?= $model["firstName"] ?>">
                </div>
                <div class="w-50">
                    <label>Last name</label>
                    <input type="text" class="input" placeholder="Last name" name="lastName"
                        value="<?= $model["lastName"] ?>">
                </div>
            </div>
            <div class="input-group mb-4">
                <label>Username</label>
                <input type="text" class="input" placeholder="Username" name="username"
                    value="<?= $model["username"] ?>">
            </div>
            <div class="input-group mb-4">
                <label>Email</label>
                <input type="text" class="input" placeholder="Email" name="email"
                    value="<?= $model["email"] ?>">
            </div>
            <div class="input-group mb-4">
                <label>Phone number</label>
                <input type="text" class="input" placeholder="Phone number" name="phone"
                    value="<?= $model["phone"] ?>">
            </div>
            <div class="input-group mb-4">
                <label>Password</label>
                <input type="password" class="input" placeholder="Password" name="password"
                    value="<?= $model["password"] ?>">
            </div>
            <div class="input-group mb-4">
                <label>Confirm password</label>
                <input type="password" class="input" placeholder="Confirm password" name="confirmPassword"
                    value="<?= $model["confirmPassword"] ?>">
            </div>

            <button class="btn btn-black w-100">Register</button>
        </form>
    </div>

    <div class="third-party mb-4">
        <button class="btn btn-google w-100 mb-3">Or sign in with Google</button>
        <button class="btn btn-facebook w-100 ">Or sign in with Facebook</button>
    </div>

    <div class="other-actions">
        <span>Already have an account?</span><a href="/login">Log in now</a>
    </div>
</div>


<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Auth/AuthLayout.php";
?>