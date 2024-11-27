<?php

use App\core\App;

$title = "Bach Khoa Clothes";
$viewData["title"] = "Product Manager";

$this
    ->addScript("Product.js")
    ->addStylesheet("Product.css")
    ->addLibraryScript("https://cdn.tiny.cloud/1/pblavam10kzc2jmu8nobtm3ah47pgaw34jtkg8qdpxreavzu/tinymce/7/tinymce.min.js");

ob_start();
?>

<div class="product-manager">
    <div class="accordion" id="parentAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <b>Basic Product Information</b>
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#parentAccordion">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mt-3 ms-4" data-bs-toggle="modal" data-bs-target="#modal" onclick="showCreateModal()">
                    Create product
                </button>

                <div class="accordion-body">
                    <!-- Basic Product Information begin -->
                    <table id="productTable" class="table table-bordered mt-2">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Price</th>
                                <th scope="col">Rate</th>
                                <th scope="col">Category</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Status</th>
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
                    <!-- Basic Product Information end -->
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <b>Product details and images</b>
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#parentAccordion">
                <div class="accordion-body">
                    <!-- Finding tool begin -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group has-validation mb-3">
                                <span class="input-group-text" id="basic-addon1">Id</span>
                                <input type="text" class="form-control" placeholder="Product ID" aria-describedby="basic-addon1" name="id" id="idProductInput">
                                <button type="button" class="btn btn-primary" onclick="findProductById(event)">Find</button>
                                <div id="idProductFeedback" class="invalid-feedback">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group has-validation mb-3">
                                <span class="input-group-text" id="basic-addon1">Slug</span>
                                <input type="text" class="form-control" placeholder="Product Slug" aria-describedby="basic-addon1" name="slug" id="slugProductInput">
                                <button type="button" class="btn btn-primary" onclick="findProductBySlug(event)">Find</button>
                                <div id="slugProductFeedback" class="invalid-feedback">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Finding tool end -->

                    <!-- Found Product Table begin -->
                    <table id="foundProductTable" class="table table-bordered mt-2">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Price</th>
                                <th scope="col">Rate</th>
                                <th scope="col">Slug</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <!--Found Product Table end -->

                    <!-- Edit Details and Images begin -->
                    <nav>
                        <div class="nav nav-tabs row" id="nav-tab" role="tablist">
                            <button class="nav-link active col-md-6" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">Product Details</button>
                            <button class="nav-link col-md-6" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Images</button>
                        </div>
                    </nav>

                    <div class="tab-content pt-3" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">

                            <textarea id="productDetails"></textarea>
                            <div class="d-flex justify-content-end mt-3">
                                <button id="saveProductDetailBtn" class="btn btn-primary px-5 disabled" data-product-id="" onclick="saveProductDetailsHTML(event)">Save</button>
                            </div>
                            <!-- Preview begin -->
                            <div class="accordion mt-3" id="accordionPreview">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePreview" aria-expanded="true" aria-controls="collapsePreview">
                                            <b>Preview</b>
                                        </button>
                                    </h2>
                                    <div id="collapsePreview" class="accordion-collapse collapse show" data-bs-parent="#accordionPreview">
                                        <div class="accordion-body">
                                            <div id="productDetailsPreview">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!--Preview end -->

                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
                            <!-- Upload Image begin -->
                            <div class="accordion" id="accordionUpload">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUpload" aria-expanded="true" aria-controls="collapseUpload">
                                            <b>Upload Images</b>
                                        </button>
                                    </h2>
                                    <div id="collapseUpload" class="accordion-collapse collapse" data-bs-parent="#accordionUpload">
                                        <div class="accordion-body">
                                            <!-- Upload Image Field begin -->
                                            <form id="uploadImageForm" class="image-uploader" enctype="multipart/form-data">
                                                <input type="file" name="image" hidden accept="image/*" required>
                                                <div class="image-displayer">
                                                    <i class="bi bi-cloud-arrow-up-fill"></i>
                                                    <p>Upload File!</p>
                                                </div>
                                            </form>
                                            <button class="btn btn-primary" id="uploadImageButton">Upload image</button>
                                            <!-- Upload Image Field end -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Upload Image end -->

                            <!-- Images table begin -->
                            <table id="imageTable" class="table table-bordered mt-2">
                                <thead>
                                    <tr>
                                        <th scope="col">Id</th>
                                        <th scope="col">Large Image</th>
                                        <th scope="col">Small Image</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="4" style="text-align: center; font-size: 16px;">No image uploaded!</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Images table end -->
                        </div>
                    </div>
                    <!-- Edit Details and Images end -->

                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog custom-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closeModalButton">Close</button>
                    <button type="button" class="btn btn-primary" id="submitModalButton">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <rect width="100%" height="100%" fill="#ff3838"></rect>
                </svg>
                <strong class="me-auto toast-title" style="color: #ff3838;"></strong>
                <small>Just now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include App::getLayoutDirectory() . "/Admin/AdminLayout.php";
?>