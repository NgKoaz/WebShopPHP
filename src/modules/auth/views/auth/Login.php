<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$this
    ->addScript("Login.js")
    ->addStylesheet("Login.css");

ob_start();
echo $viewData["TempMessage"];
?>


<div class="login-container">
    <h4 class="main-title">Nice to see you again</h4>
    <div class="login-form">
        <form method="POST" action="/login">
            <div class="input-group mb-4">
                <label>Username</label>
                <input type="text" class="input" placeholder="Email or phone number"
                    name="username"
                    value="<?= $model["username"] ?>">

            </div>
            <div class="input-group mb-4">
                <label>Password</label>
                <input type="password" class="input" placeholder="Password"
                    name="password"
                    value="<?= $model["password"] ?>">
            </div>

            <div class="account-actions">
                <div class="checkbox-input">
                    <input type="checkbox" id="rememberMe" name="rememberMe">
                    <span for="rememberMe">Remember me</span>
                </div>
                <div><a href="/forgot-password">Forgot password?</a></div>
            </div>

            <button class="btn btn-black w-100">Sign in</button>
        </form>
    </div>

    <div class="third-party">
        <button class="btn btn-google w-100 mb-3">Or sign in with Google</button>
        <button class="btn btn-facebook w-100 ">Or sign in with Facebook</button>
    </div>

    <div class="other-actions">
        <span>Dont have an account?</span> <a href="/register">Register now</a>
    </div>
</div>


<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Auth/AuthLayout.php";
?>