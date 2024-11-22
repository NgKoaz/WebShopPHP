<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=kid_star" /> -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=kid_star" /> -->

    <link rel="stylesheet" href="/src/layout/global.css">
    <link rel="stylesheet" href="/src/layout/User/UserLayout.css">
    <?php $this->loadStylesheets() ?>
</head>

<body>
    <div class="root-container">
        <!-- Header begin -->
        <?php
        if (!(isset($viewData["IS_LOGGED_IN"]) && $viewData["IS_LOGGED_IN"]))
            echo '<header>Sign up and get 20% off to your first order. <a href="/login">Sign Up Now</a></header>';
        ?>
        <nav>
            <div class="hamburger">
                <img src="\public\images\homepage\icons\hamburger-icon.png">
            </div>
            <div class="logo"><a href="/">BK.CO</a></div>

            <div class="nav-tab-list">
                <a href="/categories">Shop</a>
                <a onclick="onClickNavLink(event, 0)">New Arrivals</a>
                <a onclick="onClickNavLink(event, 1)">Top Selling</a>
            </div>

            <form id="searchFormPc" class="search-bar">
                <i class="fa-solid fa-magnifying-glass icon-24"></i>
                <input type="text" placeholder=" Search for products..." oninput="onChangeSearchInputPc(event)">

                <div class="search-result-container">
                    <ul class="search-results">
                        <!-- <li class="itemResult">
                            <a class="itemLink" href="#">
                                <div class="image">
                                    <img src="/public/images/cart/p1.png">
                                </div>
                                <div class="info">
                                    <div class="title">Mu asdf dasda dasd sd dsah ksh kghs khdfk asd khsjh fks</div>
                                    <div class="price">$1238</div>
                                </div>
                            </a>
                        </li>
                        <li class="more-results">
                            <button id="moreResultsBtn">More results</button>
                        </li> -->
                    </ul>
                </div>
            </form>


            <div class="icon-list">
                <i id="icon-search" class="fa-solid fa-magnifying-glass icon-24" onclick="onSearchIconClick(event)"></i>
                <a href="/cart"><i class="fa-solid fa-cart-shopping icon-24"></i></a>
                <div class="dropdown">
                    <button class="dropdown-btn">
                        <i class="fa-regular fa-circle-user icon-24" onclick="onProfileIconClick(event)"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <a href="/admin">
                            <li class="dropdown-item">Switch to Admin</li>
                        </a>
                        <li class="dropdown-item">Setting</li>
                        <li class="dropdown-item" onclick="sendLogoutRequest(event)">Logout</li>
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
                    <!-- <li class="itemResult">
                        <a class="itemLink" href="#">
                            <div class="image">
                                <img src="/public/images/cart/p1.png">
                            </div>
                            <div class="info">
                                <div class="title">Mu asdf dasda dasd sd dsah ksh kghs khdfk asd khsjh fks</div>
                                <div class="price">$1238</div>
                            </div>
                        </a>
                    </li>
                    <li class="more-results">
                        <button id="moreResultsBtn">More results</button>
                    </li> -->
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
            <div class="subscription">
                <h1>STAY UPTO DATE ABOUT OUR LATEST OFFERS</h1>
                <div class="subscription-form-container">
                    <form>
                        <i class="bi bi-envelope-at-fill"></i>

                        <input type="text" name="email" placeholder="Enter your email address">
                        <button>Subscribe to Newsletter</button>
                    </form>
                </div>
            </div>
            <div class="footer-container">
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
        </footer>
        <!-- Footer end -->
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous" defer></script>
    <script src="/src/layout/User/UserLayout.js" crossorigin="anonymous" defer></script>
    <?php $this->loadScripts() ?>
    <script src="https://kit.fontawesome.com/f521236fc5.js" crossorigin="anonymous" defer></script>
</body>

</html>