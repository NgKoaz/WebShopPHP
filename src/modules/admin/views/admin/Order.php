<?php

use App\core\App;

$title = "Order Manager";
$viewData["title"] = "Order Manager";

$this
    ->addScript("Order.js")
    ->addStylesheet("Order.css");

ob_start();
?>

<div class="order-manager">
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <b>Prepare Order</b>
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <table id="prepareOrderTable" class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Created at</th>
                                <th scope="col">Pay method</th>
                                <th scope="col">PSP</th>
                                <th scope="col">Order status</th>
                                <th scope="col">Bill status</th>
                                <th scope="col">Price</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    <b>All Orders</b>
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <form class="input-group mb-3" onsubmit="OrderTable.gI().findById(event)">
                        <span class="input-group-text" id="basic-addon1">Find by id</span>
                        <input type="text" class="form-control" name="billId" placeholder="Id" aria-label="Bill Id" aria-describedby="basic-addon1">
                        <button class="btn btn-primary" id="basic-addon1">Find</button>
                    </form>
                    <table id="orderTable" class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Created at</th>
                                <th scope="col">Pay method</th>
                                <th scope="col">PSP</th>
                                <th scope="col">Order status</th>
                                <th scope="col">Bill status</th>
                                <th scope="col">Price</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination justify-content-end">
                            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Admin/AdminLayout.php";
?>