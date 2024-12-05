<?php

use App\core\App;

$title = "Reset Password";
$this
    ->addScript("ResetPassword.js")
    ->addStylesheet("ResetPassword.css");

ob_start();
?>

<div class="reset-password-container">
    <h4 class="main-title">Reset your password!</h4>
    <div class="forgot-password-form">
        <form onsubmit="onSubmitForm(event)">
            <div class="input-group mb-4">
                <label for="passwordInput">Password</label>
                <input type="password" id="passwordInput" class="input" placeholder="New password"
                    name="password">
                <div id="passwordInputFeedback" class="invalid-feedback"></div>
            </div>

            <div class="input-group mb-4">
                <label for="confirmInput">Confirm password</label>
                <input type="password" id="confirmInput" class="input" placeholder="Confirm password">
                <div id="confirmPasswordFeedback" class="invalid-feedback"></div>
            </div>

            <button class="btn btn-black w-100">Reset password</button>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Auth/AuthLayout.php";
?>