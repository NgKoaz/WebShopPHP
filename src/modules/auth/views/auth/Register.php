<?php

use App\core\App;

$title = "Register";
$this
    ->addScript("Register.js")
    ->addStylesheet("Register.css");

$googleAuthURL = $viewData["GoogleAuthURL"];

ob_start();
?>

<div class="register-container">
    <h4 class="main-title mb-4">Welcome a new customer</h4>
    <div class="register-form">
        <form onsubmit="onSubmitForm(event)">
            <div class="composite-input mb-4">
                <div class="w-50">
                    <label>First name</label>
                    <input type="text" class="input" id="firstnameInput" placeholder="First name" name="firstname"
                        value="<?= $viewData["firstname"] ?>">
                    <div id="firstnameInvalidFeedback" class="invalid-feedback"></div>
                </div>
                <div class="w-50">
                    <label>Last name</label>
                    <input type="text" class="input" id="lastnameInput" placeholder="Last name" name="lastname"
                        value="<?= $viewData["lastname"] ?>">
                    <div id="lastnameInvalidFeedback" class="invalid-feedback"></div>
                </div>
            </div>
            <div class="input-group mb-4">
                <label>Username</label>
                <input type="text" class="input" id="usernameInput" placeholder="Username" name="username">
                <div id="usernameInvalidFeedback" class="invalid-feedback"></div>
            </div>
            <div class="input-group mb-4">
                <label>Email</label>
                <input type="text" class="input" id="emailInput" placeholder="Email" name="email"
                    value="<?= $viewData["email"] ?>">
                <div id="emailInvalidFeedback" class="invalid-feedback"></div>
            </div>
            <div class="input-group mb-4">
                <label>Phone number</label>
                <input type="text" class="input" id="phoneInput" placeholder="Phone" name="phone">
                <div id="phoneInvalidFeedback" class="invalid-feedback"></div>
            </div>
            <div class="input-group mb-4">
                <label>Password</label>
                <input type="password" class="input" id="passwordInput" placeholder="Password" name="password">
                <div id="passwordInvalidFeedback" class="invalid-feedback"></div>
            </div>
            <div class="input-group mb-4">
                <label>Confirm password</label>
                <input type="password" class="input" id="confirmInput" placeholder="Confirm password">
                <div id="confirmInvalidFeedback" class="invalid-feedback"></div>
            </div>

            <button class="btn btn-black w-100">Register</button>
        </form>
    </div>

    <div class="third-party mb-4">
        <a class="btn btn-google w-100 mb-3" href="<?= $googleAuthURL ?>"><i class="bi bi-google"></i> Or sign in with Google</a>
        <!-- <button class="btn btn-facebook w-100 ">Or sign in with Facebook</button> -->
    </div>

    <div class="other-actions">
        <span>Already have an account?</span><a href="/login">Log in now</a>
    </div>
</div>


<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Auth/AuthLayout.php";
?>