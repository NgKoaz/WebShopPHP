const tbody = document.querySelector("#productTable tbody");
const modal = document.querySelector("#modal");
const modalTitle = document.querySelector("#modal .modal-title")
const modalBody = document.querySelector("#modal .modal-body")
const submitModalButton = document.querySelector("#submitModalButton");
const closeModalButton = document.querySelector("#closeModalButton");

const pagination = document.querySelector(".pagination");

const tbodyFoundProductTable = document.querySelector("#foundProductTable tbody");
const productDetailsPreview = document.querySelector("#productDetailsPreview");

const saveProductDetailBtn = document.querySelector("#saveProductDetailBtn");

const imageTable = document.querySelector("#imageTable tbody");
const uploadImageForm = document.querySelector("#uploadImageForm");
const imageInput = uploadImageForm.querySelector("input[type='file']");
const imageDisplayer = uploadImageForm.querySelector(".image-displayer");
const uploadImageButton = document.querySelector("#uploadImageButton");

state = {
    products: [],
    isDeleted: false,
    currentPage: 1,
    totalPages: 1,
    autoGenerate: true,
    detailEditProduct: null
}


initTinyMCE("#productDetails");

//#region TinyMCE
function initTinyMCE(selector) {
    tinymce.init({
        selector: selector,
        setup: function (editor) {
            editor.on('input', function () {
                saveProductDetailBtn.classList.remove("disabled");
                updatePreview(editor.getContent());
            });
            editor.on('change', function () {
                saveProductDetailBtn.classList.remove("disabled");
                updatePreview(editor.getContent());
            });;
        },
        plugins: [
            // Core editing features
            'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
            // Your account includes a free trial of TinyMCE premium features
            // Try the most popular premium features until Dec 9, 2024:
            'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown',
            // Early access to document converters
            'importword', 'exportword', 'exportpdf'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
        exportpdf_converter_options: { 'format': 'Letter', 'margin_top': '1in', 'margin_right': '1in', 'margin_bottom': '1in', 'margin_left': '1in' },
        exportword_converter_options: { 'document': { 'size': 'Letter' } },
        importword_converter_options: { 'formatting': { 'styles': 'inline', 'resets': 'inline', 'defaults': 'inline', } },
    });
}
//#endregion

//#region Utility
function formatCurrency(price) {
    return Math.round(price).toLocaleString('en-US', { style: 'currency', currency: 'USD' });
}

function generateSlug(slug) {
    const normalizeVietnamese = (str) => {
        const accents = {
            'à': 'a', 'á': 'a', 'ả': 'a', 'ạ': 'a', 'ã': 'a', 'â': 'a', 'ầ': 'a', 'ấ': 'a', 'ẩ': 'a', 'ậ': 'a', 'ă': 'a', 'ắ': 'a', 'ằ': 'a', 'ẳ': 'a', 'ẵ': 'a', 'ặ': 'a',
            'è': 'e', 'é': 'e', 'ẻ': 'e', 'ẹ': 'e', 'ẽ': 'e', 'ê': 'e', 'ề': 'e', 'ế': 'e', 'ể': 'e', 'ệ': 'e',
            'ì': 'i', 'í': 'i', 'ỉ': 'i', 'ị': 'i', 'ĩ': 'i',
            'ò': 'o', 'ó': 'o', 'ỏ': 'o', 'ọ': 'o', 'õ': 'o', 'ô': 'o', 'ồ': 'o', 'ố': 'o', 'ổ': 'o', 'ộ': 'o', 'ơ': 'o', 'ỡ': 'o', 'ở': 'o', 'ờ': 'o', 'ớ': 'o',
            'ù': 'u', 'ú': 'u', 'ủ': 'u', 'ụ': 'u', 'ũ': 'u', 'ư': 'u', 'ừ': 'u', 'ứ': 'u', 'ử': 'u', 'ự': 'u', 'ữ': 'u',
            'ỳ': 'y', 'ý': 'y', 'ỷ': 'y', 'ỵ': 'y', 'ỹ': 'y',
            'đ': 'd', 'Đ': 'd',
            'ç': 'c', 'Ç': 'c'
        };
        return str.split('').map(char => accents[char] || char).join('');
    };
    return normalizeVietnamese(slug)
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
}
//#endregion



// START TABLE
function updateTable(data) {
    const products = data["products"] ?? [];
    state["products"] = products;

    tbody.innerHTML = ""
    products.forEach(product => {
        tbody.innerHTML += `
            <th scope="row">${product.id}</th>
            <td>${product.name}</td>
            <td>${product.quantity}</td>
            <td>${formatCurrency(product.price)}</td>
            <td>${product.rate}</td>
            <td>${product.category_name ?? "NULL"}</td>
            <td>${product.slug}</td>
            <th style="color: ${product.isDeleted ? Color.Red : Color.DarkGreen}">${product.isDeleted ? "Deactive" : "Active"}</th>
            <td data-id="${product.id}">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal" onclick="showDetailModal(event)">
                    Detail
                </button>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal" onclick="showEditModal(event)">
                    Edit
                </button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal" onclick="showDeleteModal(event)">
                    Delete
                </button>
            </td>
        </tr>`
    });
}

function changePage(page) {
    if (page <= 0 || page > state.totalPages || page === state.currentPage) return;
    refreshDataForTable(page);
}

function updatePagination(data) {
    const totalPages = data["totalPages"] ?? 1;
    const currentPage = data["currentPage"] ?? 1;

    state.currentPage = currentPage;
    state.totalPages = totalPages

    let page = 1;
    pagination.innerHTML = `<li class="page-item ${state.currentPage <= 1 ? "disabled" : ""}"><a class="page-link" href="#" onclick="changePage(${state.currentPage - 1})">Previous</a></li>`;
    while (page <= totalPages) {
        pagination.innerHTML += `<li class="page-item ${state.currentPage == page ? "active" : ""}"><a class="page-link" href="#" onclick="changePage(${page})">${page}</a></li>`;
        page++;
    }
    pagination.innerHTML += `<li class="page-item ${state.currentPage >= state.totalPages ? "disabled" : ""}"><a class="page-link" href="#" onclick="changePage(${state.currentPage + 1})">Next</a></li>`;
}

function refreshDataForTable(page = 1, limit = 12) {
    $.ajax({
        url: `/api/admin/products?page=${page}&limit=${limit}`,
        method: 'GET',
        success: function (response) {
            console.log(response);
            updateTable(response);
            updatePagination(response);
        },
        error: function (xhr, status, error) {
            Toast.gI().showError("Non-expected error. Please, reload page!")
        }
    });
}

refreshDataForTable();



//#region CREATE PRODUCT MODAL
function onChangeName(event, slugSelector) {
    if (!state.autoGenerate) return;
    slugObject = document.querySelector(slugSelector);
    if (slugObject) slugObject.value = generateSlug(event.target.value)
}

function showCreateModal() {
    state.autoGenerate = true;
    const title = "Create an product";
    const body = `
        <form id="modalForm" class="g-3 needs-validation" novalidate onsubmit="onCreateSubmit(event)">
            <div class="input-group mb-3 has-validation">
                <span class="input-group-text">Name</span>
                <input id="nameInput" type="text" placeholder="Name's product" class="form-control" 
                    name="name" value="" onchange="onChangeName(event, '#slugInput')">
                <div id="nameInvalidFeedback" class="invalid-feedback d-flex">
                </div>
            </div>

            <div class="input-group has-validation mb-3">
                <span class="input-group-text">Description</span>
                <textarea class="form-control" placeholder="Product's description" id="descriptionInput" rows="4" name="description"></textarea>
                <div id="descriptionInvalidFeedback" class="invalid-feedback"></div>
            </div>

            <div class="mb-3 has-validation input-group">
                <span class="input-group-text">Price</span>
                <input type="text" class="form-control" id="priceInput" name="price" value="" placeholder="987..." required>
                <span class="input-group-text">VND</span>
                <div id="priceInvalidFeedback" class="invalid-feedback"></div>
            </div>

            <div class="mb-3 has-validation input-group">
                <span class="input-group-text">Quantity</span>
                <input type="text" class="form-control" id="quantityInput" name="quantity" placeholder="987..." value="" required>
                <div id="quantityInvalidFeedback" class="invalid-feedback"></div>
            </div>

            <div class="mb-3 has-validation input-group">
                <span class="input-group-text">Slug</span>
                <input type="text" class="form-control" id="slugInput" name="slug" placeholder="/t-shirt-fashion" value="" required>
                <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                    <input type="checkbox" class="btn-check" id="btncheck1" checked onchange="(() => state.autoGenerate = !state.autoGenerate)()">
                    <label class="btn btn-outline-primary" for="btncheck1">Auto generate</label>
                </div>
                <div id="slugInvalidFeedback" class="invalid-feedback"></div>
            </div>

            <div class="mb-3 has-validation input-group">
                <span class="input-group-text">Categories</span>
                <select id="categorySelect" class="form-select" name="categoryId">
                    
                </select>
                <div id="categoryInvalidFeedback" class="invalid-feedback"></div>
            </div>

        </form>
    `;

    Modal.gI().show(title, body, true, "Create", "btn-primary", () => {
        const form = $("#modalForm")[0];
        $(form).trigger("submit");
    }, () => {
        refreshCategorySelect("categorySelect");
    })
}

function refreshCategorySelect(categorySelectId) {
    const categorySelect = document.querySelector(`#${categorySelectId}`);
    categorySelect.innerHTML = `<option selected>Open this select menu</option>`;

    $.ajax({
        url: "/api/admin/categories",
        method: "GET",
        processData: false,
        contentType: false,
        success: function (response) {
            const content = response.reduce((content, category) => {
                return content + `
                    <option value="${category.id}">${category.name}</option>
                    `
            }, "");
            categorySelect.innerHTML += content;
            categorySelect.value = categorySelect.dataset.value;
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText)
        }
    });
}


function handleErrorCreateRequest(response) {
    if (response?.errors === null) return;

    inputs = {
        "name": document.querySelector("#nameInput"),
        "description": document.querySelector("#descriptionInput"),
        "price": document.querySelector("#priceInput"),
        "quantity": document.querySelector("#quantityInput"),
        "slug": document.querySelector("#slugInput"),
        "categoryId": document.querySelector("#categorySelect"),
    }

    feedbacks = {
        "name": document.querySelector("#nameInvalidFeedback"),
        "description": document.querySelector("#descriptionInvalidFeedback"),
        "price": document.querySelector("#priceInvalidFeedback"),
        "quantity": document.querySelector("#quantityInvalidFeedback"),
        "slug": document.querySelector("#slugInvalidFeedback"),
        "categoryId": document.querySelector("#categoryInvalidFeedback"),
    }

    Object.keys(inputs).forEach(key => {
        inputs[key].classList.remove("is-invalid");
        feedbacks[key].innerHTML = "";
    })

    errors = response.errors;
    Object.keys(errors).forEach(errKey => {
        // console.log(errKey, errors[errKey]);
        if (inputs[errKey]) {
            inputs[errKey].classList.add("is-invalid");
            feedbacks[errKey].innerHTML = errors[errKey].join("<br>");
        }
    })
    Toast.gI().showError("Check your error message!");
}

function handleSuccessCreateRequest(response) {
    refreshDataForTable();
    Modal.gI().close();
    Toast.gI().showSuccess("Product has been created!");
}

function onCreateSubmit(event) {
    event.preventDefault();

    const form = new FormData(event.target);
    for (var pair of form.entries()) {
        console.log(pair[0] + ': ' + pair[1]);  // Log field name and value
    }
    $.ajax({
        url: "/api/admin/products/create",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            handleSuccessCreateRequest(response);
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            handleErrorCreateRequest(JSON.parse(xhr.responseText));
        }
    });
}
//#endregion


//#region DETAIL PRODUCT MODAL
function showDetailModal(event) {
    const parent = event.target.parentElement;
    const productId = parent.dataset.id;

    const product = state["products"].filter(p => +p.id === +productId)?.[0];
    if (product === null) {
        Toast.gI().showError("Non-expected error. Reload page!");
        return;
    };

    const title = `Detail Product ID: ${product.id} `;
    const body = `
        <div class="input-group mb-3 has-validation">
            <span class="input-group-text">Name</span>
            <input id="nameInput" type="text" placeholder="Name's product" class="form-control" 
                name="name" value="${product.name}" disabled>
            <div id="nameInvalidFeedback" class="invalid-feedback d-flex">
            </div>
        </div>

        <div class="input-group has-validation mb-3">
            <span class="input-group-text">Description</span>
            <textarea class="form-control" id="descriptionInput" rows="4" name="description" disabled>${product.description}</textarea>
            <div id="descriptionInvalidFeedback" class="invalid-feedback"></div>
        </div>

        <div class="mb-3 has-validation input-group">
            <span class="input-group-text">Price</span>
            <input type="text" class="form-control" id="priceInput" name="price" value="${product.price}" placeholder="987..." required disabled>
            <span class="input-group-text">VND</span>
            <div id="priceInvalidFeedback" class="invalid-feedback"></div>
        </div>

        <div class="mb-3 has-validation input-group">
            <span class="input-group-text">Quantity</span>
            <input type="text" class="form-control" id="quantityInput" name="quantity" placeholder="987..." value="${product.quantity}" required disabled>
            <div id="quantityInvalidFeedback" class="invalid-feedback"></div>
        </div>

        <div class="mb-3 has-validation input-group">
            <span class="input-group-text">Slug</span>
            <input type="text" class="form-control" id="slugInput" name="slug" placeholder="/t-shirt-fashion" value="${product.slug}" required disabled>
            <div id="slugInvalidFeedback" class="invalid-feedback"></div>
        </div>

        <div class="mb-3 has-validation input-group">
            <span class="input-group-text">Category</span>
            <input type="text" class="form-control" value="${product.category?.name ?? "NULL"}" required disabled>
        </div>

        <div class="mb-3 has-validation input-group">
            <span class="input-group-text">Rate</span>
            <input type="text" class="form-control" value="${product.rate}" required disabled>
        </div>

        <div class="input-group mb-3 has-validation">
            <span class="input-group-text" name="status">Status</span>
            <input type="text" class="form-control" 
                style="font-weight: 700; 
                        color: ${product.isDeleted ? Color.Red : Color.DarkGreen};"
                        value=${product.isDeleted ? "Inactive" : "Active"} 
                disabled>
        </div>
    `;

    Modal.gI().show(title, body, false, "", "", null, null);
}
//#endregion


//#region EDIT PRODUCT MODAL
function toggleIsDeleted(event) {
    event.preventDefault()
    state.isDeleted = !state.isDeleted;
    const input = event.target.previousElementSibling;
    input.value = state.isDeleted ? "Inactive" : "Active";
    input.style.color = state.isDeleted ? Color.Red : Color.DarkGreen;

    // console.log(event.target);
    event.target.classList.remove("btn-danger");
    event.target.classList.remove("btn-success");
    event.target.classList.add((!state.isDeleted ? "btn-danger" : "btn-success"));
    event.target.innerHTML = !state.isDeleted ? "Inactive" : "Active";
}

function handleSuccessEditRequest(response) {
    refreshDataForTable();
    Modal.gI().close();
    Toast.gI().showSuccess("Product has been editted!");
}

function handleErrorEditRequest(response) {
    if (response?.errors === null) return;

    const inputs = {
        "name": document.querySelector("#nameInput"),
        "description": document.querySelector("#descriptionInput"),
        "price": document.querySelector("#priceInput"),
        "quantity": document.querySelector("#quantityInput"),
        "slug": document.querySelector("#slugInput"),
        "categoryId": document.querySelector("#categorySelect"),
    }

    const feedbacks = {
        "name": document.querySelector("#nameInvalidFeedback"),
        "description": document.querySelector("#descriptionInvalidFeedback"),
        "price": document.querySelector("#priceInvalidFeedback"),
        "quantity": document.querySelector("#quantityInvalidFeedback"),
        "slug": document.querySelector("#slugInvalidFeedback"),
        "categoryId": document.querySelector("#categoryInvalidFeedback"),
    }

    Object.keys(inputs).forEach(key => {
        inputs[key].classList.remove("is-invalid");
        feedbacks[key].innerHTML = "";
    })

    errors = response.errors;
    Object.keys(errors).forEach(errKey => {
        // console.log(errKey, errors[errKey]);
        if (inputs[errKey]) {
            inputs[errKey].classList.add("is-invalid");
            feedbacks[errKey].innerHTML = errors[errKey].join("<br>");
        }
    })
    Toast.gI().showError("Check your error message!");
}

function onEditSubmit(event) {
    event.preventDefault();
    form = new FormData(event.target)
    form.append("isDeleted", state.isDeleted ? 1 : 0);
    // for (var pair of form.entries()) {
    //     console.log(pair[0] + ': ' + pair[1]);  // Log field name and value
    // }
    $.ajax({
        url: "/api/admin/products/edit",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            handleSuccessEditRequest(response);
        },
        error: function (xhr, status, error) {
            // console.log(JSON.parse(xhr.responseText))
            handleErrorEditRequest(JSON.parse(xhr.responseText));
        }
    });
}

function showEditModal(event) {
    const parent = event.target.parentElement;
    const productId = parent.dataset.id;
    const product = state["products"].filter(p => +p.id === +productId)?.[0];
    if (product === null) {
        Toast.gI().showError("Non-expected error. Reload page!");
        return;
    };
    state.autoGenerate = true;
    state.isDeleted = product.isDeleted;

    const title = `Edit Product ID: ${product.id} `;
    const body = `
            <form id = "modalForm" class="g-3 needs-validation" novalidate onsubmit = "onEditSubmit(event)">
                <input type="hidden" name="id" value="${product.id}">
                <div class="input-group mb-3 has-validation">
                    <span class="input-group-text">Name</span>
                    <input id="nameInput" type="text" placeholder="Name's product" class="form-control"
                        name="name" value="${product.name}" onchange="onChangeName(event, '#slugInput')">
                        <div id="nameInvalidFeedback" class="invalid-feedback d-flex">
                        </div>
                </div>

                <div class="input-group has-validation mb-3">
                    <span class="input-group-text">Description</span>
                    <textarea class="form-control" id="descriptionInput" rows="4" name="description">${product.description}</textarea>
                    <div id="descriptionInvalidFeedback" class="invalid-feedback"></div>
                </div>

                <div class="mb-3 has-validation input-group">
                    <span class="input-group-text">$</span>
                    <input type="text" class="form-control" id="priceInput" name="price" value="${product.price}" placeholder="987..." required>
                        <div id="priceInvalidFeedback" class="invalid-feedback"></div>
                </div>

                <div class="mb-3 has-validation input-group">
                    <span class="input-group-text">Quantity</span>
                    <input type="text" class="form-control" id="quantityInput" name="quantity" placeholder="987..." value="${product.quantity}" required>
                        <div id="quantityInvalidFeedback" class="invalid-feedback"></div>
                </div>

                <div class="mb-3 has-validation input-group">
                    <span class="input-group-text">Slug</span>
                    <input type="text" class="form-control" id="slugInput" name="slug" placeholder="/t-shirt-fashion" value="${product.slug}" required>
                        <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                            <input type="checkbox" class="btn-check" id="btncheck1" checked onchange="(() => state.autoGenerate = !state.autoGenerate)()">
                                <label class="btn btn-outline-primary" for="btncheck1">Auto generate</label>
                        </div>
                        <div id="slugInvalidFeedback" class="invalid-feedback"></div>
                </div>

                <div class="mb-3 has-validation input-group">
                    <span class="input-group-text">Categories</span>
                    <select id="categorySelect" class="form-select" name="categoryId" data-value="${product.category_id}">
                        
                    </select>
                    <div id="categoryInvalidFeedback" class="invalid-feedback"></div>
                </div>

                <div class="mb-3 has-validation input-group">
                    <span class="input-group-text">Rate</span>
                    <input type="text" class="form-control" value="${product.rate}" required disabled>
                </div>

                <div class="input-group mb-3 has-validation">
                    <span class="input-group-text">Status</span>
                    <input type="text" class="form-control"
                        style="font-weight: 700; 
                color: ${product.is_deleted ? Color.Red : Color.DarkGreen};"
                        value=${product.is_deleted ? "Inactive" : "Active"}
                        disabled>
                        <button class="btn ${!product.is_deleted ? " btn-danger" : "btn-success"}" onclick="toggleIsDeleted(event)">${!product.is_deleted ? "Inactive" : "Active"}</button>
                </div>

                <div class="input-group mb-3 has-validation">
                    <span class="input-group-text">Updated At</span>
                    <input type="text" class="form-control" value=${product.updated_at} disabled>
                </div>
            </form>
        `;

    Modal.gI().show(title, body, true, "Edit", "btn-primary", () => {
        const form = $("#modalForm")[0];
        $(form).trigger("submit");
    }, () => {
        refreshCategorySelect("categorySelect");
    });
}
//#endregion


//#region DELETE PRODUCT MODAL
function handleSuccessDeleteRequest(response) {
    refreshDataForTable();
    Modal.gI().close();
    Toast.gI().showSuccess("Product has been deleted!");
}

function handleErrorDeleteRequest(response) {
    Toast.gI().showError("Non-expected error. Please, reload page!");
}

function onDeleteSubmit(event) {
    event.preventDefault();

    form = new FormData(event.target)

    $.ajax({
        url: "/api/admin/products/delete",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            handleSuccessDeleteRequest(response);
        },
        error: function (xhr, status, error) {
            handleErrorDeleteRequest(JSON.parse(xhr.responseText));
        }
    });
}

function showDeleteModal(event) {
    const parent = event.target.parentElement;
    const productId = parent.dataset.id;
    const product = state["products"].filter(p => +p.id === +productId)?.[0];
    if (product === null) {
        Toast.gI().showError("Non-expected error. Reload page!");
        return;
    };


    const title = `Delete Product ID: ${product.id} `;
    const body = `
        <form id = "modalForm" class="g-3 needs-validation" novalidate onsubmit = "onDeleteSubmit(event)">
            <input type="hidden" name="id" value="${product.id}">
            <div class="input-group mb-3 has-validation">
                <span class="input-group-text" id="basic-addon1">Name</span>
                <input type="text" class="form-control" value="${product.name}" disabled>
            </div>
            <div class="input-group mb-3 has-validation">
                <span class="input-group-text">Quantity</span>
                <input type="text" class="form-control" value=${product.quantity} disabled>
            </div>
        </form>
    `;

    Modal.gI().show(title, body, true, "Delete", "btn-danger", () => {
        const form = $("#modalForm")[0];
        $(form).trigger("submit");
    });
}
//#endregion



//#region Product detail and iamges.
function updatePreview(content) {
    productDetailsPreview.innerHTML = content;
}

function saveProductDetailsHTML(event) {
    const form = new FormData();
    const editor = tinymce.get("productDetails");

    form.append("id", event.target.dataset.productId);
    form.append("details", editor.getContent());

    $.ajax({
        url: "/api/admin/products/details/edit",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            Toast.gI().showSuccess("Saved");
            saveProductDetailBtn.classList.add("disabled");
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            Toast.gI().showError("Perhaps, you need to select product first!");
            // handleErrorEditRequest(JSON.parse(xhr.responseText));
        }
    });
}


function changeOrderImage(event, productId, index, tend) {
    const images = state.detailEditProduct.images;

    if (tend > 0) {
        if (+index <= 0) {
            Toast.gI().showError("Cannot go up anymore");
            return;
        }

        const temp = images[+index - 1];
        images[+index - 1] = images[+index];
        images[+index] = temp;
    } else {
        if (+index >= images.length - 1) {
            Toast.gI().showError("Cannot go down anymore");
            return;
        }

        const temp = images[+index + 1];
        images[+index + 1] = images[+index];
        images[+index] = temp;
    }

    const form = new FormData();
    form.append("productId", productId);
    form.append("images", JSON.stringify(images));
    console.log(JSON.stringify(images));

    $.ajax({
        url: `/api/admin/products/images/order`,
        method: 'POST',
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            handleFindingSuccess(response);
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            Toast.gI().showError("Select product first!");
        }
    });
}


function handleFindingSuccess(product) {
    state.detailEditProduct = product;
    console.log(product);
    // Clear Error
    const inputs = {
        "id": document.querySelector("#idProductInput"),
        "slug": document.querySelector("#slugProductInput"),
    }
    const feedbacks = {
        "id": document.querySelector("#idProductFeedback"),
        "slug": document.querySelector("#slugProductFeedback"),
    }
    Object.keys(inputs).forEach(key => {
        inputs[key].classList.remove("is-invalid");
        feedbacks[key].innerHTML = "";
    })

    // Import data
    tbodyFoundProductTable.innerHTML = `
        <tr>
            <th>${product.id}</th>
            <td>${product.name}</td>
            <td>${product.quantity}</td>
            <td>${formatCurrency(product.price)}</td>
            <td>${product.rate}</td>
            <td>${product.slug}</td>
        </tr>
    `;

    if (!product.details) product.details = "";

    const editor = tinymce.get("productDetails");
    editor.setContent(product.details);
    productDetailsPreview.innerHTML = product.details;
    saveProductDetailBtn.setAttribute("data-product-id", product.id);


    uploadImageForm.querySelector("input[type='hidden']")?.remove();
    const productIdHiddenInput = document.createElement("input");
    productIdHiddenInput.type = "hidden";
    productIdHiddenInput.name = "productId";
    productIdHiddenInput.value = product.id;

    uploadImageForm.insertAdjacentElement("afterbegin", productIdHiddenInput);


    product.images = JSON.parse(product.images ?? "[]");
    if (product.images.length <= 0) {
        imageTable.innerHTML = `
            <td colspan="4" style="text-align: center; font-size: 16px;">No image uploaded!</td>
        `;
    } else {
        const images = product.images;
        const content = images.reduce((content, { lg: lgImg, sm: smImg }, index) => {
            return content + `
                <tr>
                    <th scope="col">${index}</th>
                    <td scope="col"><img src="${lgImg}" style="max-width: 300px; max-height: 300px; object-fit:cover"></td>
                    <td scope="col"><img src="${smImg}" style="max-width: 100px; max-height: 100px;"></td>
                    <td scope="col">
                        <button class="btn btn-primary" onclick="changeOrderImage(event, '${product.id}', ${index}, 1)">Up</button>
                        <button class="btn btn-success" onclick="changeOrderImage(event, '${product.id}', ${index}, -1)">Down</button>
                        <button class="btn btn-danger" onclick="deleteProductImage(event, '${product.id}', '${lgImg}')">Delete</button>
                    </td>
                </tr>
            `
        }, "");
        imageTable.innerHTML = content;

    }
}

function handleFindingProductError(response) {
    if (response?.errors === null) return;

    const inputs = {
        "id": document.querySelector("#idProductInput"),
        "slug": document.querySelector("#slugProductInput"),
    }

    const feedbacks = {
        "id": document.querySelector("#idProductFeedback"),
        "slug": document.querySelector("#slugProductFeedback"),
    }

    Object.keys(inputs).forEach(key => {
        inputs[key].classList.remove("is-invalid");
        feedbacks[key].innerHTML = "";
    })

    errors = response.errors;
    Object.keys(errors).forEach(errKey => {
        // console.log(errKey, errors[errKey]);
        if (inputs[errKey]) {
            inputs[errKey].classList.add("is-invalid");
            feedbacks[errKey].innerHTML = errors[errKey].join("<br>");
        }
    })
    Toast.gI().showError("Check your error message!");
}

function findProductById(event) {
    const idProductInput = document.querySelector("#idProductInput");
    const id = idProductInput.value;

    $.ajax({
        url: `/api/admin/product?id=${id}`,
        method: 'GET',
        success: function (response) {
            console.log(response);
            handleFindingSuccess(response);
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            handleFindingProductError(JSON.parse(xhr.responseText));
        }
    });
}

function findProductBySlug(event) {
    const slugProductInput = document.querySelector("#slugProductInput");
    const slug = slugProductInput.value;

    $.ajax({
        url: `/api/admin/product?slug=${slug}`,
        method: 'GET',
        success: function (response) {
            console.log(response);
            handleFindingSuccess(response);
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            handleFindingProductError(JSON.parse(xhr.responseText));
        }
    });
}


//#endregion 


//#region Upload Image
function handleUploadSuccess(response) {

}

imageInput.addEventListener("change", (event) => {
    const file = event.target.files[0];
    if (!file) {
        imageDisplayer.classList.remove("show");
        imageDisplayer.innerHTML = `
            <i class="bi bi-cloud-arrow-up-fill"></i>
            <p>Upload File!</p>
        `;
        return;
    }
    const reader = new FileReader();
    reader.onload = () => {
        const url = reader.result;
        imageDisplayer.classList.add("show");
        imageDisplayer.innerHTML = `
            <img src="${url}" style="height: 100%;">
        `;
    }
    reader.readAsDataURL(file);
})

uploadImageForm.onclick = (event) => {
    const fileInput = event.target.querySelector("input[type='file']");
    fileInput?.click();
}

uploadImageButton.onclick = (event) => {
    const fileInput = document.querySelector("#uploadImageForm input[type='file']");
    if (fileInput.files.length <= 0) {
        Toast.gI().showError("Select image!");
        return;
    }

    const form = new FormData(uploadImageForm);
    for (const pair of form.entries()) console.log(pair);

    $.ajax({
        url: `/api/admin/products/image/edit`,
        method: 'POST',
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            handleFindingSuccess(response);

            fileInput.value = "";
            fileInput.dispatchEvent(new Event("change"));
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            Toast.gI().showError("Select product first!");
        }
    });
}


function deleteProductImage(event, productId, lgImg) {
    const form = new FormData();
    form.append("productId", productId);
    form.append("image", lgImg);

    $.ajax({
        url: `/api/admin/products/image/delete`,
        method: 'POST',
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            handleFindingSuccess(response);
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

//#endregion