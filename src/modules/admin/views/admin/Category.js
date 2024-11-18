const selector = {}
const state = {}
selector.categories = document.querySelector(".categories");
state.autoGenerate = true;
state.autoSave = true;

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

function toggleAutoGenerate() {
    state.autoGenerate = !state.autoGenerate;
}

function toggleAutoSave() {
    state.autoSave = !state.autoSave;
}

categories = [
    {
        id: 1,
        name: "Catjieji123s",
        slug: "cdfgdfomiaf124dlsdi",
        level: 0,
    },
    {
        id: 2,
        name: "fdagds",
        slug: "xxx",
        level: 0,
    },
    {
        id: 3,
        name: "132",
        slug: "aaa",
        level: 0,
    },
    {
        id: 4,
        name: "gg2",
        slug: "sdf",
        level: 0,
    },
];

function refreshCategories() {
    categories.forEach(c => addCategory(c));
}


function autoGenerateSlug(event, cElementId) {
    if (!state.autoGenerate) return;
    const cObj = document.querySelector(`#${cElementId} input[name='slug']`);
    if (cObj) cObj.value = generateSlug(event.target.value);
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
            </div>
        </div>
        <div class="col-md-6 d-flex justify-content-start align-items-center">
            <button class="btn btn-success">Save</button>

            <button class="plus-btn" onclick="addCategory(null, '${cElementId}')"><i class="bi bi-patch-plus-fill"></i></button>

            <button class="minus-btn" onclick="deleteCategory('${cElementId}')"><i class="bi bi-patch-minus-fill"></i></button>
        </div>
    `;

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


