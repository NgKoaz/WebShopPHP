<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$this
    ->addScript("index.js")
    ->addStylesheet("index.css");

ob_start();
?>

<div class="app-container">

    <!-- Welcome section start -->
    <div class="welcome">
        <div class="left">
            <h1 class="big-welcome-text">FIND CLOTHES THAT MATCHES YOUR STYLE</h1>
            <p class="welcome-description">Browse through our diverse range of meticulously crafted garments, designed to bring out your individuality and cater to your sense of style.</p>
            <a href="/categories"><button class="shopnow-btn">Shop Now</button></a>
            <div class="figure-list">
                <div class="figure">
                    <h4 class="figure-title">200+</h4>
                    <p class="figure-description">International Brands</p>
                </div>
                <div class="figure">
                    <h4 class="figure-title">2,000+</h4>
                    <p class="figure-description">High-Quality Products</p>
                </div>
                <div class="figure">
                    <h4 class="figure-title">30,000+</h4>
                    <p class="figure-description">Happy Customers</p>
                </div>
            </div>
        </div>
        <div class="right">
            <img src="/public/images/homepage/welcome.png" alt="Welcome">
        </div>
    </div>
    <!-- Welcome section end -->

    <!-- Brand list start -->
    <div class="brand-list">
        <picture>
            <source media="(min-width: 1240px)" srcset="/public/images/homepage/logos/pc/versace-logo.png">
            <img src="/public/images/homepage/logos/mobile/versace.png">
        </picture>
        <picture>
            <source media="(min-width: 1240px)" srcset="/public/images/homepage/logos/pc/zara-logo.png">
            <img src="/public/images/homepage/logos/mobile/zara.png">
        </picture>
        <picture>
            <source media="(min-width: 1240px)" srcset="/public/images/homepage/logos/pc/gucci-logo.png">
            <img src="/public/images/homepage/logos/mobile/gucci.png">
        </picture>
        <picture>
            <source media="(min-width: 1240px)" srcset="/public/images/homepage/logos/pc/prada-logo.png">
            <img src="/public/images/homepage/logos/mobile/prada.png">
        </picture>
        <picture>
            <source media="(min-width: 1240px)" srcset="/public/images/homepage/logos/pc/calvin-logo.png">
            <img src="/public/images/homepage/logos/mobile/calvin.png">
        </picture>
    </div>

    <!-- Brand list end -->

    <!-- New arrival start -->
    <div class="new-arrival">
        <h2 class="section-title">NEW ARRIVALS</h2>
        <div class="card-list">
            <!-- <div class="card">
                <img src="/public/images/newarrivals/cloth1.png">
                <h3 class="title">T-SHIRT WITH TAPE DETAILS</h3>
                <div class="stars">

                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-half star-ic"></i>
                    <i class="bi bi-star star-ic"></i>


                    <span>5/5</span>
                </div>
                <div class="price">
                    $140
                </div>
            </div> -->

        </div>

        <div class="btn-view-all-container">
            <a href="/categories?options=%7B%0A%22order%22%3A%20%22created_at%22%0A%7D">View All</a>
        </div>
    </div>
    <!-- New arrival end -->

    <!-- Delimeter -->
    <hr class="delimeter">
    <!-- Delimeter -->


    <!-- Top selling start -->
    <div class="top-selling">
        <h2 class="section-title">TOP SELLING</h2>
        <div class="card-list">
            <div class="card">
                <img src="/public/images/newarrivals/cloth1.png">
                <h3 class="title">T-SHIRT WITH TAPE DETAILS</h3>
                <div class="stars">
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-half star-ic"></i>
                    <i class="bi bi-star star-ic"></i>
                    <span>5/5</span>
                </div>
                <div class="price">
                    $140
                </div>
            </div>

            <div class="card">
                <img src="/public/images/newarrivals/cloth2.png">
                <h3 class="title">T-SHIRT WITH TAPE DETAILS</h3>
                <div class="stars">

                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-half star-ic"></i>
                    <i class="bi bi-star star-ic"></i>
                    <span>5/5</span>
                </div>
                <div class="price">
                    $140
                </div>
            </div>

            <div class="card">
                <img src="/public/images/newarrivals/cloth3.png">
                <h3 class="title">T-SHIRT WITH TAPE DETAILS</h3>
                <div class="stars">
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-half star-ic"></i>
                    <i class="bi bi-star star-ic"></i>
                    <span>5/5</span>
                </div>
                <div class="price">
                    $140
                </div>
            </div>

            <div class="card">
                <img src="/public/images/newarrivals/cloth4.png">
                <h3 class="title">T-SHIRT WITH TAPE DETAILS</h3>
                <div class="stars">

                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-half star-ic"></i>
                    <i class="bi bi-star star-ic"></i>
                    <span>5/5</span>
                </div>
                <div class="price">
                    $140
                </div>
            </div>
        </div>

        <div class="btn-view-all-container">
            <a href="/categories?options=%7B%0A%22order%22%3A%20%22sold_number%22%0A%7D">View All</a>
        </div>
    </div>
    <!-- Top selling end -->

    <!-- Browse style start -->
    <div class="browse-style">
        <div class="title-container">
            <h2 class="section-title">BROWSE BY DRESS STYLE</h2>
        </div>
        <div class="styles">
            <div class="style-card" data-href="/categories/casual">
                <h4 class="style-title">Casual</h4>
                <picture>
                    <source media="(min-width: 768px)" srcset="/public/images/homepage/browse/lg/casual.png">
                    <img src="/public/images/homepage/browse/sm/casual.png">
                </picture>
            </div>
            <div class="style-card" data-href="/categories/formal">
                <h4 class="style-title">Formal</h4>
                <picture>
                    <source media="(min-width: 768px)" srcset="/public/images/homepage/browse/lg/formal.png">
                    <img src="/public/images/homepage/browse/sm/formal.png">
                </picture>
            </div>
            <div class="style-card" data-href="/categories/party">
                <h4 class="style-title">Party</h4>
                <picture>
                    <source media="(min-width: 768px)" srcset="/public/images/homepage/browse/lg/party.png">
                    <img src="/public/images/homepage/browse/sm/party.png">
                </picture>
            </div>
            <div class="style-card" data-href="/categories/gym">
                <h4 class="style-title">Gym</h4>
                <picture>
                    <source media="(min-width: 768px)" srcset="/public/images/homepage/browse/lg/gym.png">
                    <img src="/public/images/homepage/browse/sm/gym.png">
                </picture>
            </div>
        </div>
    </div>
    <!-- Browse style end -->

    <!-- Testimonial start -->
    <div class="testimonial">
        <div class="title-container">
            <h2 class="section-title">OUR HAPPY CUSTOMERS</h2>
            <div class="testimonial-icon-list">
                <i class="fa-solid fa-arrow-left icon-24 prev"></i>
                <i class="fa-solid fa-arrow-right icon-24 next"></i>
            </div>
        </div>

        <div class="testimonial-cards">
            <div class="testimonial-card">
                <div class="stars">
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-half star-ic"></i>
                    <i class="bi bi-star star-ic"></i>
                </div>
                <div class="author-name">
                    Sarah M. <i class="bi bi-check-circle-fill verify-ic"></i>
                </div>
                <p class="comment">
                    "I'm blown away by the quality and style of the clothes I received from Shop.co. From casual wear to elegant dresses, every piece I've bought has exceeded my expectations.”
                </p>
            </div>

            <div class="testimonial-card">
                <div class="stars">
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-half star-ic"></i>
                    <i class="bi bi-star star-ic"></i>
                </div>
                <div class="author-name">
                    Sarah M. <i class="bi bi-check-circle-fill verify-ic"></i>
                </div>
                <p class="comment">
                    "I'm blown away by the quality and style of the clothes I received from Shop.co. From casual wear to elegant dresses, every piece I've bought has exceeded my expectations.”
                </p>
            </div>

            <div class="testimonial-card">
                <div class="stars">
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-fill star-ic"></i>
                    <i class="bi bi-star-half star-ic"></i>
                    <i class="bi bi-star star-ic"></i>
                </div>
                <div class="author-name">
                    Sarah M. <i class="bi bi-check-circle-fill verify-ic"></i>
                </div>
                <p class="comment">
                    "I'm blown away by the quality and style of the clothes I received from Shop.co. From casual wear to elegant dresses, every piece I've bought has exceeded my expectations.”
                </p>
            </div>
        </div>
    </div>
    <!-- Testimonial end -->
</div>


<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/User/UserLayout.php";
?>