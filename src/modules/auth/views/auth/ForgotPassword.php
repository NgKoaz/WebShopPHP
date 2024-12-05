<?php

use App\core\App;

$title = "Forgot password";
$this
    ->addScript("ForgotPassword.js")
    ->addStylesheet("ForgotPassword.css");

ob_start();
?>


<div class="forgot-password-container">
    <h4 class="main-title">We'll take back!</h4>
    <div class="forgot-password-form">
        <form onsubmit="onSubmitForm(event)">
            <div class="input-group mb-4">
                <label for="emailInput">Email</label>
                <input type="email" id="emailInput" class="input" placeholder="Email"
                    name="email">
                <div id="emailInputFeedback" class="invalid-feedback"></div>
            </div>

            <button class="btn btn-black w-100">Reset password</button>
        </form>
    </div>

    <div class="other-actions">
        <span>Already remember?</span> <a href="/login">Log in here</a>
    </div>
</div>


<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Auth/AuthLayout.php";
?>