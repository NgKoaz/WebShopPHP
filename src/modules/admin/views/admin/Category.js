const selector = {}
const state = {}
const storage = {}
selector.categories = document.querySelector(".categories");
state.autoGenerate = true;
state.categories = []

storage.greenColor = "#32ff7e";
storage.darkGreenColor = "#3ae374";
storage.redColor = "#ff3838";

selector.toastLive = document.getElementById('liveToast');
selector.toastBootstrap = bootstrap.Toast.getOrCreateInstance(selector.toastLive);
selector.toastTitle = document.querySelector(".toast .toast-title");
selector.toastBody = document.querySelector(".toast .toast-body");
selector.toastRect = document.querySelector(".toast rect");


//#region Utility
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
    return normalizeVietnamese(slug.toLowerCase())
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .replace(/^-|-$/g, '');
}
//#endregion

//#region TOAST
function showErrorToast(title, message) {
    selector.toastRect.setAttribute('fill', storage.redColor);
    selector.toastTitle.innerHTML = title;
    selector.toastTitle.style.color = storage.redColor;
    selector.toastBody.innerHTML = message;
    selector.toastBootstrap.show();
}

function showSuccessToast(title, message) {
    selector.toastRect.setAttribute('fill', storage.greenColor);
    selector.toastTitle.innerHTML = title;
    selector.toastTitle.style.color = storage.greenColor;
    selector.toastBody.innerHTML = message;
    selector.toastBootstrap.show();
}
//#endregion

function toggleAutoGenerate() {
    state.autoGenerate = !state.autoGenerate;
}

function renderCategories(response) {
    selector.categories.innerHTML = '';
    state.categories = response;
    state.categories.forEach(c => addCategory(c));
}

function refreshCategories() {
    $.ajax({
        url: `/api/admin/categories`,
        method: 'GET',
        success: function (response) {
            console.log(response);
            renderCategories(response);
        },
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
        }
    });
}

function autoGenerateSlug(event, cElementId) {
    if (!state.autoGenerate) return;
    const cObj = document.querySelector(`#${cElementId} input[name='slug']`);
    if (cObj) cObj.value = generateSlug(event.target.value);
}

function handleSaveError(response, cElementId) {
    if (response?.errors === null) return;
    if (response?.errors?.id) {
        showErrorToast("Error!", "Non-expected error. Please, reload page!");
        return;
    }
    console.log(`#${cElementId} input[name='name']`);

    inputs = {
        "name": document.querySelector(`#${cElementId} input[name='name']`),
        "slug": document.querySelector(`#${cElementId} input[name='slug']`),
    }

    feedbacks = {
        "name": document.querySelector(`#${cElementId} .nameInvalidFeedback`),
        "slug": document.querySelector(`#${cElementId} .slugInvalidFeedback`)
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

function onSaveClick(cElementId) {
    const cObj = document.querySelector(`#${cElementId}`);
    const nameInput = document.querySelector(`#${cElementId} input[name='name']`);
    const slugInput = document.querySelector(`#${cElementId} input[name='slug']`);

    const form = new FormData();
    form.append("id", cObj.dataset.id ?? "");
    form.append("name", nameInput.value ?? "");
    form.append("slug", slugInput.value ?? "");
    if (cObj.dataset.parentId) {
        parentElement = document.querySelector(`#${cObj.dataset.parentId}`);
        form.append("parentId", parentElement.id);
    }
    // for (var pair of form.entries()) {
    //     console.log(pair[0] + ': ' + pair[1]);
    // }
    $.ajax({
        url: `/api/admin/categories/${cObj.dataset.id ? "update" : "create"}`,
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            showSuccessToast("Success!", `Category has been ${cObj.dataset.id ? "updated" : "created"}!`);
            refreshCategories();
        },
        error: function (xhr, status, error) {
            handleSaveError(JSON.parse(xhr.responseText), cElementId);
        }
    });
}

function onDeleteClick(cElementId) {
    const cObj = document.querySelector(`#${cElementId}`);

    if (cObj.dataset.id) {
        const form = new FormData();
        form.append("id", cObj.dataset.id ?? "");
        $.ajax({
            url: `/api/admin/categories/delete`,
            method: "POST",
            data: form,
            processData: false,
            contentType: false,
            success: function (response) {
                deleteCategory(cElementId);
                showSuccessToast("Success!", `Category has been ${cObj.dataset.id ? "updated" : "created"}!`);
                refreshCategories();
            },
            error: function (xhr, status, error) {
                handleSaveError(JSON.parse(xhr.responseText), cElementId);
            }
        });

        return;
    }

    deleteCategory(cElementId);
}

function addCategory(cData, parentElementId) {
    const parentElement = document.getElementById(parentElementId);
    const level = (parentElement === null) ? 0 : parseInt(parentElement.dataset.level) + 1;
    cData ??= {};
    cElementId = "C" + crypto.randomUUID();

    cElement = document.createElement("div");
    cElement.id = cElementId;
    cElement.className = "category row align-items-baseline";
    cElement.style.marginLeft = `${45 * level}px`;

    if (cData.id) cElement.setAttribute('data-id', `${cData.id}`);
    if (parentElementId) cElement.setAttribute('data-parent-id', `${parentElementId ?? ""}`);
    cElement.setAttribute('data-level', `${level}`);

    cElement.innerHTML = `
        <div class="col-md-5">
            <div class="input-group mb-3">
                <span class="input-group-text">Category</span>
                <input type="text" class="form-control" placeholder="Category's name" aria-label="Category's name" name="name" value="${cData.name ?? ""}" onchange="autoGenerateSlug(event, '${cElementId}')">
                <span class="input-group-text">Slug</span>
                <input type="text" class="form-control" placeholder="Slug" aria-label="Slug" name="slug" value="${cData.slug ?? ""}">
                <div class="invalid-feedback d-flex">
                    <div class="nameInvalidFeedback col-md-6">
                    </div>
                    <div class="slugInvalidFeedback col-md-6">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-start align-items-center">
            <button class="btn btn-success" onclick="onSaveClick('${cElementId}')">Save</button>

            <button class="plus-btn ms-5 d-inline-block" onclick=""><i class="bi bi-patch-plus-fill"></i></button>

            <button class="minus-btn ms-3" onclick="onDeleteClick('${cElementId}')"><i class="bi bi-patch-minus-fill"></i></button>
        </div>
    `;
    //addCategory(null, '${cElementId}')
    nextSibling = parentElement?.nextSibling;
    if (nextSibling) selector.categories.insertBefore(cElement, nextSibling);
    else selector.categories.appendChild(cElement);
}

function changeLevel(categoryNode, direction) {
    let level = parseInt(categoryNode.dataset.level);
    if (level === 0) return;

    level = +level + ((direction < 0) ? -1 : 1);
    categoryNode.setAttribute('data-level', `${level}`);
    categoryNode.style.marginLeft = `${45 * level}px`;

    const childs = document.querySelectorAll(`.category[data-parent-id='${categoryNode.id}']`);
    [...childs].forEach(child => changeLevel(child, direction));
}

function deleteCategory(cElementId) {
    const currentObj = document.getElementById(cElementId);
    const childs = document.querySelectorAll(`.category[data-parent-id='${cElementId}']`)
    if (childs) [...childs].forEach(c => {
        changeLevel(c, -1);
        c.dataset.parentId = currentObj.dataset.parentId;
    });
    currentObj?.remove();
}

refreshCategories();


