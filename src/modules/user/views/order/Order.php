<?php

use App\core\App;

$title = "Orders";
$this
    ->addScript("Order.js")
    ->addStylesheet("Order.css");

ob_start();
?>


<div class="app-container">
    <div class="breadcrumb">
        <a href="/">Home</a> >
        <a href="/orders">Your order</a>
    </div>

    <h4 class="title">Your order</h4>
    <div class="tab-slider" data-state="UNPAID">
        <div class="tab-item selected" data-state="UNPAID">
            Unpaid
        </div>
        <div class="tab-item" data-state="PREPARING">
            Preparing
        </div>
        <div class="tab-item" data-state="SHIPPING">
            Shipping
        </div>
        <div class="tab-item" data-state="SHIPPED">
            Shipped
        </div>
        <div class="tab-item" data-state="RECEIVED">
            Received
        </div>
        <div class="tab-item" data-state="CANCELLED">
            Cancelled
        </div>
    </div>
    <div class="card-list">
        <div class="card-container">
            <div class="card">
                <div class="card-top">
                    <div class="shop-name">BK.CO</div>
                    <div class="status">Unpaid</div>
                </div>
                <div class="product-container">
                    <div class="product-list">
                        <div class="product">
                            <img src="/public/upload/images/1674ae6152c9031732961813.png">
                            <div class="product-info">
                                <div class="product-title">Gradient Graphic T-shirt</div>
                                <div class="product-quantity">x1</div>
                                <div class="product-price">$25.99</div>
                            </div>
                        </div>
                    </div>
                    <button class="card-more-btn">More <i class="bi bi-arrow-bar-down"></i></button>
                </div>
                <div class="total-price">Total price (1 product): $12312312</div>
                <div class="actions">
                    <button class="card-btn card-btn-primary">Received</button>
                    <button class="card-btn">Cancel</button>
                </div>
            </div>
        </div>




        <!-- <div id="pcApp">
        <table id="orderTable" class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Created at</th>
                    <th>Order status</th>
                    <th>Payment status</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div id="mobileApp">

    </div> -->
    </div>
</div>



<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/User/UserLayout.php";
?>