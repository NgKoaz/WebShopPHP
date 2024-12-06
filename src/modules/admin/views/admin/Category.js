const selector = {}
const state = {}
const storage = {}
selector.categories = document.querySelector(".categories");
state.autoGenerate = true;
state.draggable = false;
state.categories = [];


storage.greenColor = "#32ff7e";
storage.darkGreenColor = "#3ae374";
storage.redColor = "#ff3838";



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



function isAllSave() {
    const saveBtns = document.querySelectorAll("[data-save-btn]");
    return ![...saveBtns].some(saveBtn => saveBtn.style.display !== "none");
}

function toggleDraggable() {
    state.draggable = !state.draggable;
    const categories = document.querySelectorAll(".category");
    categories.forEach(category => category.draggable = state.draggable);
}


function toggleAutoGenerate() {
    state.autoGenerate = !state.autoGenerate;
}

function renderCategories(response) {
    state.oldResponse = response;
    selector.categories.innerHTML = '';
    state.categories = response;
    state.categories.forEach(c => !c.parentId && addCategory(c, "", response));
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
    const category = event.target.closest(".category");
    const saveBtn = category.querySelector("[data-save-btn]");
    saveBtn.style.display = "";

    if (!state.autoGenerate) return;
    const cObj = document.querySelector(`#${cElementId} input[name='slug']`);
    if (cObj) cObj.value = generateSlug(event.target.value);
}

function onSlugChange(event) {
    const category = event.target.closest(".category");
    const saveBtn = category.querySelector("[data-save-btn]");
    saveBtn.style.display = "";
}

function handleSaveError(response, cElementId) {
    if (response?.errors === null) return;
    if (response?.errors?.id) {
        Toast.gI().showError("Non-expected error. Please, reload page!");
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
    Toast.gI().showError("Check your error message!");
}

function onSaveClick(cElementId) {
    const cObj = document.querySelector(`#${cElementId}`);
    const parentSaveBtn = document.querySelector(`#${cObj.dataset.parentId} [data-save-btn]`);
    if (parentSaveBtn && parentSaveBtn.style.display !== "none") {
        Toast.gI().showError("You have to save parent category first!");
        return;
    }

    const nameInput = document.querySelector(`#${cElementId} input[name='name']`);
    const slugInput = document.querySelector(`#${cElementId} input[name='slug']`);

    const form = new FormData();
    form.append("id", cObj.dataset.id ?? "");
    form.append("name", nameInput.value ?? "");
    form.append("slug", slugInput.value ?? "");
    if (cObj.dataset.parentId) {
        parentElement = document.querySelector(`#${cObj.dataset.parentId}`);
        form.append("parentId", parentElement.dataset.id);
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
            Toast.gI().showSuccess(`Category has been ${cObj.dataset.id ? "updated" : "created"}!`);
            refreshCategories();
            console.log(response)
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText)
            try {
                handleSaveError(JSON.parse(xhr.responseText), cElementId);
            } catch (err) {

            }
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
                Toast.gI().showSuccess(`Category has been ${cObj.dataset.id ? "updated" : "created"}!`);
                refreshCategories();
            },
            error: function (xhr, status, error) {
                handleSaveError(JSON.parse(xhr.responseText), cElementId);
                refreshCategories(state.oldResponse);
            }
        });
        return;
    }
    deleteCategory(cElementId);
}

function onDecreaseLevel(cElementId) {
    if (!isAllSave()) {
        Toast.gI().showError("Save all categories before create a new one");
        return;
    }

    const cElement = document.querySelector(`#${cElementId}`);
    const parentElement = document.querySelector(`#${cElement.dataset.parentId}`);
    const grandpaElement = (parentElement) ? document.querySelector(`#${parentElement.dataset.parentId}`) : null;
    const nameInput = cElement.querySelector("input[name='name']");
    const slugInput = cElement.querySelector("input[name='slug']");

    const form = new FormData();
    form.append("id", cElement.dataset.id);
    form.append("name", nameInput.value);
    form.append("slug", slugInput.value);
    form.append("parentId", grandpaElement?.dataset.id ?? 0);

    $.ajax({
        url: `/api/admin/categories/update`,
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response)
            Toast.gI().showSuccess("Category has been changed!");
            refreshCategories();
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            Toast.gI().showError("Some errors occur!");
            renderCategories(state.oldResponse);
        }
    });
}

function addCategory(cData, parentElementId, data) {
    if (!isAllSave()) {
        Toast.gI().showError("Save all categories before create a new one");
        return;
    }

    const parentElement = document.getElementById(parentElementId);
    const level = (parentElement === null) ? 0 : parseInt(parentElement.dataset.level) + 1;
    cData ??= {};
    const cElementId = "C" + crypto.randomUUID();

    const cElement = document.createElement("div");
    cElement.id = cElementId;
    cElement.draggable = state.draggable;
    cElement.className = "category row align-items-baseline";
    cElement.style.marginLeft = `${45 * level}px`;
    cElement.ondragstart = onDragStart;
    cElement.ondragend = onDragEnd;
    cElement.ondragover = onDragOver;
    // cElement.ondragenter = onDragEnter;
    cElement.ondragleave = onDragLeave;
    cElement.ondrop = onDrop;

    if (cData.id) cElement.setAttribute('data-id', `${cData.id}`);
    if (parentElementId) cElement.setAttribute('data-parent-id', `${parentElementId ?? ""}`);
    cElement.setAttribute('data-level', `${level}`);

    cElement.innerHTML = `
        <div class="col-md-5">
            <div class="input-group mb-3">
                <span class="input-group-text">Category</span>
                <input type="text" class="form-control" placeholder="Category's name" aria-label="Category's name" name="name" value="${cData.name ?? ""}" oninput="autoGenerateSlug(event, '${cElementId}')">
                <span class="input-group-text">Slug</span>
                <input type="text" class="form-control" placeholder="Slug" aria-label="Slug" name="slug" value="${cData.slug ?? ""}" oninput="onSlugChange(event)">
                <div class="invalid-feedback d-flex">
                    <div class="nameInvalidFeedback col-md-6">
                    </div>
                    <div class="slugInvalidFeedback col-md-6">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-start align-items-center">
            <button class="btn btn-success" data-save-btn onclick="onSaveClick('${cElementId}')">Save</button>

            <button class="plus-btn ms-3 d-inline-block" onclick="addCategory(null, '${cElementId}')"><i class="bi bi-patch-plus-fill"></i></button>

            <button class="minus-btn ms-3" onclick="onDeleteClick('${cElementId}')"><i class="bi bi-patch-minus-fill"></i></button>

            ${level ? `<button class="btn btn-primary ms-3" onclick="onDecreaseLevel('${cElementId}')">Decrease Level</button>` : ""}
        </div>
    `;

    const nextSibling = parentElement?.nextSibling;
    if (nextSibling) selector.categories.insertBefore(cElement, nextSibling);
    else selector.categories.appendChild(cElement);

    if (cData.id) {
        cElement.querySelector("[data-save-btn]").style.display = "none";
    }

    if (data) data.forEach(c => {
        if (cData.id === c.parentId) addCategory(c, cElementId, data)
    })
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
    const parent = currentObj.parentNode;
    return detachedElement = currentObj ? parent.removeChild(currentObj) : null;
}

refreshCategories();



//#region Drag
function onDragStart(event) {
    event.target.classList.add("dragging");
}

function onDragEnd(event) {
    event.target.classList.remove("dragging");
};

function onDrop(event) {
    event.preventDefault();
    if (!isAllSave()) {
        Toast.gI().showError("Save all categories before create a new one");
        return;
    }
    const draggingItem = document.querySelector(".dragging");
    const targetItem = event.target.closest(".category");
    targetItem.classList.remove("drag-over");

    if (!targetItem.dataset.id) {
        Toast.gI().showError("You have to save that category before drop into it!");
        return;
    }

    if (targetItem && targetItem.id !== draggingItem.dataset.parentId && targetItem !== draggingItem) {
        const childItem = deleteCategory(draggingItem.id);
        childItem.dataset.parentId = targetItem.id;
        const newLevel = +targetItem.dataset.level + 1;
        childItem.dataset.level = newLevel;

        childItem.style.marginLeft = `${45 * +newLevel}px`;
        const nextSibling = targetItem?.nextSibling;
        if (nextSibling) selector.categories.insertBefore(childItem, nextSibling);
        else selector.categories.appendChild(childItem);


        const nameInput = childItem.querySelector("input[name='name']");
        const slugInput = childItem.querySelector("input[name='slug']");
        const form = new FormData();
        form.append("id", childItem.dataset.id);
        form.append("name", nameInput.value);
        form.append("slug", slugInput.value);
        form.append("parentId", targetItem.dataset.id);

        $.ajax({
            url: `/api/admin/categories/update`,
            method: "POST",
            data: form,
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response)
                Toast.gI().showSuccess("Category has been changed!");
                refreshCategories();
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
                Toast.gI().showError("Some errors occur!");
                renderCategories(state.oldResponse);
            }
        });

    }
}

function onDragLeave(event) {
    event.preventDefault();
    const draggingItem = document.querySelector(".dragging");
    const targetItem = event.target.closest(".category");
    if (targetItem && targetItem !== draggingItem)
        targetItem.classList.remove("drag-over");
}

function onDragOver(event) {
    event.preventDefault();
    const draggingItem = document.querySelector(".dragging");
    const targetItem = event.target.closest(".category");
    if (targetItem && targetItem !== draggingItem)
        targetItem.classList.add("drag-over");
}
//#endregion