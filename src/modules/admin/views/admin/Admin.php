<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$viewData["title"] = "Overview";

$this
    ->addScript("Admin.js")
    ->addLibraryScript("https://cdn.jsdelivr.net/npm/apexcharts")
    ->addStylesheet("Admin.css");

ob_start();
?>

<div class="app-container">
    <div class="left">
        <h4 class="ml-10px">Overview</h4>
        <div class="info-container">
            <div class="card-container">
                <div class="custom-card" data-name="EARNING">
                    <i class="bi bi-cash-stack icon-32"></i>
                    <div>
                        <div class="custom-title">Earnings</div>
                        <div class="custom-body"></div>
                    </div>
                </div>
            </div>

            <div class="card-container">
                <div class="custom-card" data-name="ORDER">
                    <i class="bi bi-boxes icon-32"></i>
                    <div>
                        <div class="custom-title">Completed Order</div>
                        <div class="custom-body"></div>
                    </div>
                </div>
            </div>

            <div class="card-container">
                <div class="custom-card" data-name="USER">
                    <i class="bi bi-people icon-32"></i>
                    <div>
                        <div class="custom-title">Total Users</div>
                        <div class="custom-body"></div>
                    </div>
                </div>
            </div>

            <div class="card-container">
                <div class="custom-card" data-name="WAITING">
                    <i class="bi bi-hourglass-split icon-32"></i>
                    <div>
                        <div class="custom-title">Waiting Order</div>
                        <div class="custom-body"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="chart-container">
            <h4 class="ml-10px">Earnings</h4>
            <div class="top-chart">
                <div id="earningChart" style="width: calc(100% - 20px);"></div>
            </div>
            <div class="bottom-chart">
                <div class="bottom-chart-left">
                    <h4>Order Number</h4>
                    <div id="orderChart"></div>
                </div>
                <div class="bottom-chart-right">
                    <h4>User Number</h4>
                    <div id="userChart"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="right">
        <h4 class="ml-10px">New Order</h4>
        <div class="notify-container" id="newOrderList">
        </div>
        <a class="nav-new-order" href="/admin/orders">Go to Order Manager</a>

        <h4 class="ml-10px mt-15px">New User</h4>
        <div class="notify-container" id="newUserList">
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Admin/AdminLayout.php";
?>