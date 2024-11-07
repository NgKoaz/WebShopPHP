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
        <span>Home > Shop > Men > T-shirt</span>
    </div>
    <div class="main-content">
        <div class="product-images">
            <div class="large-image">
                <img src="/public/images/product/lg.png">
            </div>
            <div class="small-images">
                <div class=""><img src="/public/images/product/sm2.png"></div>
                <div class=""><img src="/public/images/product/sm2.png"></div>
                <div class=""><img src="/public/images/product/sm3.png"></div>
            </div>
        </div>

        <div class="product-info">
            <h1 class="title">One Life Graphic T-shirt</h1>
            <div class="stars">
                <i class="bi bi-star-fill star-ic"></i>
                <i class="bi bi-star-fill star-ic"></i>
                <i class="bi bi-star-fill star-ic"></i>
                <i class="bi bi-star-half star-ic"></i>
                <i class="bi bi-star star-ic"></i>
            </div>
            <div class="price">$260</div>
            <p class="description">This graphic t-shirt which is perfect for any occasion. Crafted from a soft and breathable fabric, it offers superior comfort and style.</p>
        </div>

        <!-- <hr>

        <div class="color-options">
            <label>Select Colors</label>
            <select>
                <option>1</option>
                <option>2</option>
            </select>
        </div> -->
        <!-- 
        <hr>

        <div class="size-options">
            <label>Choose Size</label>
            <button>Small</button>
            <button>Medium</button>
            <button>Large</button>
            <button>X-Large</button>
        </div> -->

        <hr>

        <div class="cart-section">
            <div class="quantity-modifier">
                <button>+</button>
                <button>-</button>
            </div>

            <button class="add-cart-btn">
                Add to Cart
            </button>
        </div>

        <div class="tabs">
            <div class="top">
                <div class="active">Product Details</div>
                <div>Rating & Reviews</div>
            </div>

            <div class="product-tab" hidden>
                content;
            </div>

            <div class="review-tab">
                <div class="top">
                    <h3 class="title">All reviews</h3>
                    <div class="buttons">
                        <button>Filter</button>
                        <button>Write a review</button>
                    </div>
                </div>


                <div class="reviews">
                    <div class="review-card">
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
                        <small class="date">On Post</small>
                    </div>


                    <div class="review-card">
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
                        <small class="date">On Post</small>
                    </div>


                    <div class="review-card">
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
                        <small class="date">On Post</small>
                    </div>


                    <div class="review-card">
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
                        <small class="date">On Post</small>
                    </div>
                </div>


                <div class="load-more-btn-container">
                    <button class="load-more-btn">Load More Reviews</button>
                </div>
            </div>
        </div>

        <div class="other-products">
            <h2 class="title-section">YOU MIGHT ALSO LIKE</h2>
            <div class="products">
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
            </div>
        </div>
    </div>


</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/layout.php";
?>