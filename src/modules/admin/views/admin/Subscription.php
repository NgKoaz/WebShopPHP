<?php

use App\core\App;

$title = "Subscriptions";
$viewData["title"] = "Subscription Manager";

$this
    ->addScript("Subscription.js")
    ->addStylesheet("Subscription.css")
    ->addLibraryScript("https://cdn.tiny.cloud/1/0544y3lrrvypwmfug20zuogts1y3isoyvkfge9d3gvqrsgqz/tinymce/7/tinymce.min.js");

ob_start();
?>


<div class="subscription-manager">
    <div class="accordion" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <b>Subscription List</b>
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <!-- Subscription Table Begin -->
                    <table id="subscriptionTable" class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th scope="col">Email</th>
                                <th scope="col">Verified</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <!-- Subscription Table End -->
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <b>Compose email</b>
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <!-- Email Composing Begin -->
                    <textarea id="emailComposer"></textarea>
                    <div class="email-actions mt-3 d-flex justify-content-end">
                        <button id="broadcastBtn" class="btn btn-primary">Broadcast</button>
                    </div>
                    <!-- Email Composing End -->
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Admin/AdminLayout.php";
?>