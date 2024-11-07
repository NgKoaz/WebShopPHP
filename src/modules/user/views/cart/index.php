<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$this
    ->addScript("index.js")
    ->addStylesheet("index.css");

ob_start();
?>

<div class="app-container">
    <div class="breadcrumb">
        <span>Home > Cart</span>
    </div>

    <h1 class="main-title">Your Cart</h1>

    <div class="main-content">
        <div class="items">
            <div class="item">
                <div class="left">
                    <img src="/public/images/cart/p1.png">
                </div>

                <div class="right">
                    <div class="top">
                        <div class="info">
                            <div class="name">Gradient Graphic T-shirt</div>
                            <div class="size">Size: <span>Large</span></div>
                            <div class="color">Color: <span>White</span></div>
                        </div>

                        <div class="delele-icon-container">
                            <i class="bi bi-trash-fill"></i>
                        </div>
                    </div>

                    <div class="bottom">
                        <div class="price">$92390</div>

                        <div class="quantity-modifier">
                            1
                            <i class="bi bi-dash-lg minus"></i>
                            <i class="bi bi-plus-lg plus"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="item">
                <div class="left">
                    <img src="/public/images/cart/p1.png">
                </div>

                <div class="right">
                    <div class="top">
                        <div class="info">
                            <div class="name">Gradient Graphic T-shirt</div>
                            <div class="size">Size: <span>Large</span></div>
                            <div class="color">Color: <span>White</span></div>
                        </div>

                        <div class="delele-icon-container">
                            <i class="bi bi-trash-fill"></i>
                        </div>
                    </div>

                    <div class="bottom">
                        <div class="price">$92390</div>

                        <div class="quantity-modifier">
                            1
                            <i class="bi bi-dash-lg minus"></i>
                            <i class="bi bi-plus-lg plus"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="item">
                <div class="left">
                    <img src="/public/images/cart/p1.png">
                </div>

                <div class="right">
                    <div class="top">
                        <div class="info">
                            <div class="name">Gradient Graphic T-shirt</div>
                            <div class="size">Size: <span>Large</span></div>
                            <div class="color">Color: <span>White</span></div>
                        </div>

                        <div class="delele-icon-container">
                            <i class="bi bi-trash-fill"></i>
                        </div>
                    </div>

                    <div class="bottom">
                        <div class="price">$92390</div>

                        <div class="quantity-modifier">
                            1
                            <i class="bi bi-dash-lg minus"></i>
                            <i class="bi bi-plus-lg plus"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="order-summary">
            <h4 class="title">Order Summary</h4>

            <div class="bill-details">
                <div class="sm-entry">
                    <div>Subtotal</div>
                    <div>$2839</div>
                </div>
                <div class="sm-entry">
                    <div>Discount (-20%)</div>
                    <div>-$113</div>
                </div>
                <div class="sm-entry">
                    <div>Delivery Fee</div>
                    <div>$15</div>
                </div>
            </div>

            <div class="lg-entry">
                <div>Total</div>
                <div>$467</div>
            </div>

            <div class="promotion-code">
                <input type="text" placeholder="Add promo code">
                <button>Apply</button>
            </div>

            <button class="checkout-btn">Go to Checkout -></button>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/layout.php";
?>