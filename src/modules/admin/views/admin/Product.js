const tbody = document.querySelector("#productTable tbody");
const modal = document.querySelector("#modal");
const modalTitle = document.querySelector("#modal .modal-title")
const modalBody = document.querySelector("#modal .modal-body")
const submitModalButton = document.querySelector("#submitModalButton");
const closeModalButton = document.querySelector("#closeModalButton");

const pagination = document.querySelector(".pagination")

const toastLive = document.getElementById('liveToast');
const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLive);
const toastTitle = document.querySelector(".toast .toast-title");
const toastBody = document.querySelector(".toast .toast-body");
const toastRect = document.querySelector(".toast rect");

const tbodyFoundProductTable = document.querySelector("#foundProductTable tbody");
const productDetailsPreview = document.querySelector("#productDetailsPreview");

const saveProductDetailBtn = document.querySelector("#saveProductDetailBtn");


const greenColor = "#32ff7e";
const darkGreenColor = "#3ae374";
const redColor = "#ff3838";

state = {
    products: [],
    isDeleted: false,
    currentPage: 1,
    totalPages: 1,
    autoGenerate: true
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


//#region TOAST
function showErrorToast(title, message) {
    toastRect.setAttribute('fill', redColor);
    toastTitle.innerHTML = title;
    toastTitle.style.color = redColor;
    toastBody.innerHTML = message;
    toastBootstrap.show();
}

function showSuccessToast(title, message) {
    toastRect.setAttribute('fill', greenColor);
    toastTitle.innerHTML = title;
    toastTitle.style.color = greenColor;
    toastBody.innerHTML = message;
    toastBootstrap.show();
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
            <th style="color: ${product.isDeleted ? redColor : darkGreenColor}">${product.isDeleted ? "Deactive" : "Active"}</th>
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
    if (page <= 0 || page > toastTitle || page === state.currentPage) return;
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
            showErrorToast("Error!", "Non-expected error. Please, reload page!")
        }
    });
}

refreshDataForTable();





//#region MODAL SECTION 
function closeModal() {
    closeModalButton.click();
}

function updateModalSubmitButton(content, setDeleteButton) {
    submitModalButton.classList.remove("btn-primary");
    submitModalButton.classList.remove("btn-danger");
    submitModalButton.style.display = "";
    if (content) submitModalButton.innerHTML = content;
    else submitModalButton.style.display = "none";
    if (setDeleteButton) {
        submitModalButton.classList.add("btn-danger");
    } else {
        submitModalButton.classList.add("btn-primary");
    }
}
//#endregion


//#region CREATE PRODUCT MODAL
function onChangeName(event, slugSelector) {
    if (!state.autoGenerate) return;
    slugObject = document.querySelector(slugSelector);
    if (slugObject) slugObject.value = generateSlug(event.target.value)
}

function showCreateModal() {
    state.autoGenerate = true;
    modalTitle.innerHTML = "Create an product";
    modalBody.innerHTML = `
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
    updateModalSubmitButton('Create', false);
    submitModalButton.onclick = () => {
        const form = $("#modalForm")[0];
        $(form).trigger("submit");
    };

    refreshCategorySelect("categorySelect");
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
    showErrorToast("Error!", "Check your error message!");
}

function handleSuccessCreateRequest(response) {
    refreshDataForTable();
    closeModal();
    showSuccessToast("Success!", "Product has been created!");
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
            // console.log(xhr.responseText);
            handleErrorCreateRequest(JSON.parse(xhr.responseText));
        }
    });
}
//#endregion


//#region DETAIL PRODUCT MODAL
function showDetailModal(event) {
    parent = event.target.parentElement;
    productId = parent.dataset.id;

    product = state["products"].filter(p => +p.id === +productId)?.[0];
    if (product === null) {
        showErrorToast("Error!", "Non-expected error. Reload page!");
        return;
    };

    modalTitle.innerHTML = `Detail Product ID: ${product.id} `;
    modalBody.innerHTML = `
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
                            color: ${product.isDeleted ? redColor : darkGreenColor};"
                            value=${product.isDeleted ? "Inactive" : "Active"} 
                    disabled>
            </div>
                `;
    updateModalSubmitButton("", false);
}
//#endregion


//#region EDIT PRODUCT MODAL
function toggleIsDeleted(event) {
    event.preventDefault()
    state.isDeleted = !state.isDeleted;
    const input = event.target.previousElementSibling;
    input.value = state.isDeleted ? "Inactive" : "Active";
    input.style.color = state.isDeleted ? redColor : darkGreenColor;

    // console.log(event.target);
    event.target.classList.remove("btn-danger");
    event.target.classList.remove("btn-success");
    event.target.classList.add((!state.isDeleted ? "btn-danger" : "btn-success"));
    event.target.innerHTML = !state.isDeleted ? "Inactive" : "Active";
}

function handleSuccessEditRequest(response) {
    refreshDataForTable();
    closeModal();
    showSuccessToast("Success!", "Product has been editted!");
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
    showErrorToast("Error!", "Check your error message!");
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
    parent = event.target.parentElement;
    productId = parent.dataset.id;
    product = state["products"].filter(p => +p.id === +productId)?.[0];
    if (product === null) {
        showErrorToast("Error!", "Non-expected error. Reload page!");
        return;
    };
    state.autoGenerate = true;
    state.isDeleted = product.isDeleted;
    modalTitle.innerHTML = `Edit Product ID: ${product.id} `;
    modalBody.innerHTML = `
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
                                <span class="input-group-text">Price</span>
                                <input type="text" class="form-control" id="priceInput" name="price" value="${product.price}" placeholder="987..." required>
                                    <span class="input-group-text">VND</span>
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
                                <select id="categorySelect" class="form-select" name="categoryId" data-value="${product.category.id}">
                                    
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
                            color: ${product.isDeleted ? redColor : darkGreenColor};"
                                    value=${product.isDeleted ? "Inactive" : "Active"}
                                    disabled>
                                    <button class="btn ${!product.isDeleted ? " btn-danger" : "btn-success"}" onclick="toggleIsDeleted(event)">${!product.isDeleted ? "Inactive" : "Active"}</button>
                            </div>

                            <div class="input-group mb-3 has-validation">
                                <span class="input-group-text">Updated At</span>
                                <input type="text" class="form-control" value=${product.updatedAt.date} disabled>
                            </div>

                        </form>
                `;
    updateModalSubmitButton("Edit", false);
    submitModalButton.onclick = () => {
        const form = $("#modalForm")[0];
        $(form).trigger("submit");
    }

    refreshCategorySelect("categorySelect");
}
//#endregion


//#region DELETE PRODUCT MODAL
function handleSuccessDeleteRequest(response) {
    refreshDataForTable();
    closeModal();
    showSuccessToast("Success!", "Product has been deleted!");
}

function handleErrorDeleteRequest(response) {
    showErrorToast("Error!", "Non-expected error. Please, reload page!");
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
    parent = event.target.parentElement;
    productId = parent.dataset.id;
    product = state["products"].filter(p => +p.id === +productId)?.[0];
    if (product === null) {
        showErrorToast("Error!", "Non-expected error. Reload page!");
        return;
    };
    modalTitle.innerHTML = `Delete Product ID: ${product.id} `;
    modalBody.innerHTML = `
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
    updateModalSubmitButton("Delete", true);
    submitModalButton.onclick = () => {
        const form = $("#modalForm")[0];
        $(form).trigger("submit");
    }
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
        url: "/api/admin/products/detail/edit",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            showSuccessToast("Success!", "Saved");
            saveProductDetailBtn.classList.add("disabled");
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            showErrorToast("Error!", "Perhaps, you need to select product first!");
            // handleErrorEditRequest(JSON.parse(xhr.responseText));
        }
    });
}

function handleFindingSuccess(product) {
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
    showErrorToast("Error!", "Check your error message!");
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


