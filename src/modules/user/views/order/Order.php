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
    <div class="tab-slider" data-state="PREPARING">
        <div class="tab-item" data-state="UNPAID">
            Unpaid
        </div>
        <div class="tab-item selected" data-state="PREPARING">
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