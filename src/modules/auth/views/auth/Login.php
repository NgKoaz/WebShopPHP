<?php

use App\core\App;

$title = "Login";
$this
    ->addScript("Login.js")
    ->addStylesheet("Login.css");

$googleAuthURL = $viewData["GoogleAuthURL"];

ob_start();
?>


<div class="login-container">
    <h4 class="main-title">Nice to see you again</h4>
    <div class="login-form">
        <form onsubmit="onSubmitForm(event)">
            <div class="input-group mb-4">
                <label for="usernameInput">Username</label>
                <input type="text" id="usernameInput" class="input" placeholder="Email or phone number"
                    name="username">
                <div id="usernameInvalidFeedback" class="invalid-feedback"></div>
            </div>
            <div class="input-group mb-4">
                <label for="passwordInput">Password</label>
                <input type="password" id="passwordInput" class="input" placeholder="Password"
                    name="password">
                <div id="passwordInvalidFeedback" class="invalid-feedback"></div>
            </div>

            <div class="account-actions">
                <div class="checkbox-input">
                    <input type="checkbox" id="rememberMe" name="rememberMe" style="margin-bottom: 0px;">
                    <label id="labelRememberMe" for="rememberMe" style="margin-bottom: 0px;">Remember me</label>
                </div>
                <div><a href="/forgot-password" style="font-size: var(--md-font-size);">Forgot password?</a></div>
            </div>

            <button class="btn btn-black w-100">Sign in</button>
        </form>
    </div>

    <div class="third-party">
        <a class="btn btn-google w-100 mb-3" href="<?= $googleAuthURL ?>"><i class="bi bi-google"></i> Or sign in with Google</a>
        <!-- <button class="btn btn-facebook w-100 ">Or sign in with Facebook</button> -->
    </div>

    <div class="other-actions">
        <span>Dont have an account?</span> <a href="/register">Register now</a>
    </div>
</div>


<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Auth/AuthLayout.php";
?>