<?php

use App\core\Container;
use App\services\LoginManager;
use App\services\RoleManager;

$container = Container::getInstance();
$loginManager = $container->get(LoginManager::class);
$roleManager = $container->get(RoleManager::class);
$isLoggedIn = $loginManager->isLoggedIn();
$isAdmin = $roleManager->isUserHasRole($loginManager->getCurrentUser(), RoleManager::$ADMIN);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/public/images/favicon.ico">
    <title><?= $title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/src/layout/User/UserLayout.css">
    <?php $this->loadStylesheets() ?>
</head>

<body>
    <input type="hidden" name="TempMessage" value="<?= $viewData["TempMessage"] ?>" data-is-error="<?= $viewData["IsErrorMessage"] ?>">

    <div class="root-container">
        <!-- Header begin -->
        <?php
        if (!$isLoggedIn) echo '<header>Sign up and get 20% off to your first order. <a href="/login">Sign Up Now</a></header>';
        ?>
        <nav>
            <div class="hamburger">
                <img src="\public\images\homepage\icons\hamburger-icon.png">
            </div>
            <div class="logo"><a href="/">BK.CO</a></div>

            <div class="nav-tab-list">
                <a href="/categories">Shop</a>
                <a href="/categories?options=%7B%0A%22order%22%3A%20%22created_at%22%0A%7D">New Arrivals</a>
                <a href="/categories?options=%7B%0A%22order%22%3A%20%22sold_number%22%0A%7D">Top Selling</a>
            </div>

            <form id="searchFormPc" class="search-bar" onsubmit="onSubmitSearchForm(event)">
                <i class="fa-solid fa-magnifying-glass icon-24"></i>
                <input type="text" placeholder=" Search for products..." oninput="onChangeSearchInputPc(event)">

                <div class="search-result-container">
                    <ul class="search-results">
                    </ul>
                </div>
            </form>


            <div class="icon-list">
                <i id="icon-search" class="fa-solid fa-magnifying-glass icon-24" onclick="onSearchIconClick(event)"></i>
                <a id="cartIcon" href="/cart"><i class="fa-solid fa-cart-shopping icon-24"></i><span id="numInCart"></span></a>
                <div class="dropdown">
                    <a class="dropdown-btn" <?= $isLoggedIn ? "" : 'href="/login"' ?>>
                        <i class="fa-regular fa-circle-user icon-24" onclick="onProfileIconClick(event)"></i>
                    </a>
                    <ul class="dropdown-menu" <?= $isLoggedIn ? "" : 'hidden' ?>>
                        <?php
                        if ($isAdmin) {
                            echo '
                            <a href="/admin">
                                <li class="dropdown-item">Switch to Admin</li>
                            </a>';
                        }
                        ?>
                        <a href="/orders">
                            <li class="dropdown-item">Orders</li>
                        </a>
                        <li class="dropdown-item" onclick="showProfileModal(event)">Setting</li>
                        <li class="dropdown-item logout-btn" onclick="sendLogoutRequest(event)">Logout</li>
                    </ul>
                </div>
            </div>
        </nav>


        <form id="searchFormMobile" onsubmit="onSubmitLogout(event)">
            <div class="search-bar" style="height: 35px;">
                <i class="fa-solid fa-magnifying-glass icon-24" style="font-size: 20px;"></i>
                <input type="text" placeholder="Search for products..." oninput="onChangeSearchInputMb(event)">
            </div>
            <div class="search-result-container">
                <ul class="search-results">
                </ul>
            </div>
        </form>


        <form id="logoutForm" onsubmit="onSubmitLogout(event)">
            <input type="submit">
        </form>
        <!-- Header end -->


        <!-- Content begin -->
        <?= $content; ?>
        <!-- Content end -->


        <!-- Footer begin -->
        <footer>
            <div class="footer-container">
                <div class="subscription">
                    <h1>STAY UPTO DATE ABOUT OUR LATEST OFFERS</h1>
                    <div class="subscription-form-container">
                        <form onsubmit="subscribe(event)">
                            <i class="bi bi-envelope-at-fill"></i>
                            <input type="email" name="email" placeholder="Enter your email address">
                            <button>Subscribe to Newsletter</button>
                        </form>
                    </div>
                </div>
                <div class="footer-infomation">
                    <div class="left">
                        <div class="logo"><a href="/">BK.CO</a></div>
                        <p>We have clothes that suits your style and which you’re proud to wear. From women to men.</p>
                        <div class="social-media-icons">
                            <a class=" facebook-ic" href="https://www.facebook.com">
                                <img src="\public\images\homepage\icons\fb.png" alt="facebook" style="width:auto; height:auto;">
                            </a>
                            <a class="" href="https://www.x.com">
                                <img src="\public\images\homepage\icons\twitter.png" alt="x" style="width:auto; height:auto;">
                            </a>
                            <a class="" href="https://www.instagram.com">
                                <img src="\public\images\homepage\icons\ins.png" alt="twitter" style="width:auto; height:auto;">
                            </a>
                        </div>
                    </div>
                    <div class="right">
                        <div class="info-container">
                            <div class="info-list">
                                <div class="info-title">Company</div>
                                <div class="link">
                                    <a>About</a><br>
                                    <a>Features</a><br>
                                    <a>Works</a><br>
                                    <a>Career</a>
                                </div>
                            </div>
                            <div class="info-list">
                                <div class="info-title">HELP</div>
                                <div class="link">
                                    <a>Customer Support</a><br>
                                    <a>Delivery Details</a><br>
                                    <a>Terms & Conditions</a><br>
                                    <a>Privacy Policy</a>
                                </div>
                            </div>
                            <div class="info-list">
                                <div class="info-title">FAQ</div>
                                <div class="link">
                                    <a>Account</a><br>
                                    <a>Manage Deliveries</a><br>
                                    <a>Orders</a><br>
                                    <a>Payment</a>
                                </div>
                            </div>
                            <div class="info-list">
                                <div class="info-title">RESOURCES</div>
                                <div class="link">
                                    <a>Free eBook</a><br>
                                    <a>Development Tutorial</a><br>
                                    <a>How to - Blog</a><br>
                                    <a>Youtube Playlist</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="more-description">
                    <hr class="footer-delimeter">
                    <div class="rights-description"> Shop.co © 2000-2023, All Rights Reserved</div>

                    <div class="payment-badges">
                        <div class="badge">
                            <img src="\public\images\homepage\icons\visa.png" alt="twitter" style="width:100%; height:100%;">
                        </div>
                        <div class="badge">
                            <img src="\public\images\homepage\icons\msc.png" alt="twitter" style="width:100%; height:100%;">
                        </div>
                        <div class="badge">
                            <img src="\public\images\homepage\icons\pp.png" alt="twitter" style="width:100%; height:100%;">
                        </div>
                        <div class="badge">
                            <img src="\public\images\homepage\icons\gpay.png" alt="twitter" style="width:100%; height:100%;">
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Footer end -->

        <!-- Toast begin -->
        <div id="toastContainer">
        </div>
        <!-- Toast end -->

        <!-- Modal begin -->
        <div id="modalContainer">
        </div>
        <!-- Modal end -->

        <!-- Modal begin -->
        <div id="profileModalContainer">
            <div id="profileModal" class="modal" data-close-modal="#profileModal">
                <div class="modal-top">
                    <div class="modal-tabs">
                        <div class="modal-tab-item selected" data-state="BASIC">Basic Info</div>
                        <div class="modal-tab-item" data-state="AUTH">Auth Email</div>
                        <div class="modal-tab-item" data-state="CHANGE">Change Email</div>
                        <div class="modal-tab-item" data-state="PASSWORD">Change Password</div>
                    </div>
                </div>
                <div class="modal-content">
                </div>
                <div class="modal-action">
                    <button class="w-50 btn btn-secondary btn-close" onclick="closeProfileModal(event)">Cancel</button>
                    <button id="profileModalSaveBtn" class="w-50 btn btn-primary btn-submit" onclick="ModalTabManager.submitForm(event)">Save</button>
                </div>
                <div class="modal-close">
                    <i class="bi bi-x-lg" onclick="closeProfileModal(event)"></i>
                </div>
            </div>
        </div>
        <!-- Modal end -->
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous" defer></script>
    <script src="/src/layout/User/UserLayout.js" crossorigin="anonymous" defer></script>
    <?php $this->loadScripts() ?>
    <script src="https://kit.fontawesome.com/f521236fc5.js" crossorigin="anonymous" defer></script>
    <script src="public/js/snowfall.js" crossorigin="anonymous" defer></script>
</body>

</html>