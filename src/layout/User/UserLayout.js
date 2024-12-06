const subscription = document.querySelector(".subscription");
const footerContainer = document.querySelector(".footer-container");
const state = {
    isShowSearchBar: false,
    debounceTimer: null
}
const selector = {}
selector.searchFormMb = document.querySelector("#searchFormMobile");
selector.searchBarMb = document.querySelector("#searchFormMobile .search-bar");
selector.searchContainerMb = document.querySelector("#searchFormMobile .search-result-container");

selector.searchFormPc = document.querySelector("#searchFormPc")
selector.searchContainerPc = document.querySelector("#searchFormPc .search-result-container")

selector.searchResList = document.querySelectorAll(".search-results");

selector.toastContainer = document.querySelector("#toastContainer");
selector.numInCart = document.querySelector("#numInCart");

const profileModalContainer = document.querySelector("#profileModalContainer");

let toastTimer = null;


const modalTabManager = new ModalTabManager();


document.addEventListener("DOMContentLoaded", () => {
    refreshNumInCart();

    const TempMessage = document.querySelector("input[name='TempMessage']");
    if (TempMessage.value) {
        let isError = false;
        if (TempMessage.dataset.isError === "true")
            isError = true;
        openToast(TempMessage.value, isError);
    }
});

document.addEventListener("click", (event) => {
    closeSearchWhenClickOutside(event);
    closeDropdownWhenClickOutside(event);
});

const closeSearchWhenClickOutside = (event) => {
    const parent = event.target.closest(".search-result-container");
    if (!parent) {
        selector.searchContainerMb.style.display = "none";
        selector.searchContainerPc.style.display = "none";
    }
}

function closeDropdownWhenClickOutside(event) {
    const dropdown = event.target.closest(".dropdown");
    if (!dropdown) {
        const dropdownMenu = document.querySelectorAll(".dropdown-menu")
        dropdownMenu.forEach(menu => menu.classList.remove("active"))
    }
}



function openToast(message, isError) {
    selector.toastContainer.style.pointerEvents = "all";
    selector.toastContainer.innerHTML = `
    <style>
        .toast::after {
            background-color: ${isError ? `#ff3838` : `#3ae374`};
        }
    </style>
    <div id="toast" class="toast">
        <div class="toast-icon">${isError ? `<i class="bi bi-x-circle-fill" style="color: #ff3838;"></i>` : `<i class="bi bi-check-circle-fill" style="color: #3ae374;"></i>`}</div>
        <div class="toast-content">
            <div class="toast-title">${isError ? "Error!" : "Success!"}</div>
            <div class="toast-message">${message}</div>
        </div>
        <div class="toast-close" data-close-toast="#toast">
            <i class=" bi bi-x-lg" onclick="closeToast(event)"></i>
        </div>
    </div>`;

    const toast = document.querySelector(`#toast`);
    setTimeout(() => {
        toast.classList.add("show");
    }, 100);
    toastTimer = setTimeout(() => {
        selector.toastContainer.style.pointerEvents = "";
        toast.classList.remove("show");
    }, 3000);
}

function closeToast(event) {
    const toastClose = event.target.closest(".toast-close");
    if (toastClose) {
        selector.toastContainer.style.pointerEvents = "";

        const toastId = toastClose.dataset.closeToast;
        const toast = document.querySelector(`${toastId}`);
        clearTimeout(toastTimer);
        toast.classList.remove("show");
    }
}

//#region Log Out Request
function sendLogoutRequest(event) {
    $.ajax({
        url: `/api/logout`,
        method: "POST",
        processData: false,
        contentType: false,
        success: function (response) {
            window.location.href = "/";
        },
        error: function (xhr, status, error) {
            console.log(JSON.parse(xhr.responseText));
        }
    });
}
//#endregion



//#region Icon Interaction
function onSearchIconClick(event) {
    state.isShowSearchBar = !state.isShowSearchBar;
    if (state.isShowSearchBar) {
        selector.searchFormMb.style.display = "block";
        selector.searchBarMb.style.display = "block";
    } else {
        selector.searchFormMb.style.display = "none";
        selector.searchBarMb.style.display = "none";
    }
}


function onProfileIconClick(event) {
    const dropdown = event.target.closest(".dropdown");
    dropdown.querySelector(".dropdown-menu").classList.add("active");

}
//#endregion


//#region Search PC
function onChangeSearchInputPc(event) {
    sendSearchRequestMb(event.target.value);
}

function onSubmitSearchForm(event) {
    event.preventDefault();
    document.querySelector("#moreResultsBtn")?.click();
}

//#endregion



//#region Search Request
function handleSuccessSearchRequest(response, searchQuery) {
    const products = response.products;
    const moreNum = response.moreNum
    if (products?.length <= 0) {
        if (selector.searchResList.length > 0)
            [...selector.searchResList].forEach(selector => selector.innerHTML = `
                <li class="no-results">
                    Not found any result.
                </li>`
            );
        return;
    }

    const content = products.reduce((content, product) => {
        const images = JSON.parse(product.images ?? "[]");

        return content +
            `
            <li class="itemResult">
                <a class="itemLink" href="/products/${product.slug}">
                    <div class="image">
                        <img src="${images.length !== 0 ? images[0]["sm"] : "/public/images/sm_no_image.webp"}">
                    </div>
                    <div class="info">
                        <div class="title">${product.name}</div>
                        <div class="price">$${product.price}</div>
                    </div>
                </a>
            </li>
        `
    }, "");


    if (selector.searchResList.length > 0) {
        if (moreNum) {
            [...selector.searchResList].forEach(selector => selector.innerHTML = content + `
                <li class="more-results">
                    <a id="moreResultsBtn" href="/categories?query=${searchQuery}">More ${moreNum} results</a>
                </li>`
            );
        } else {
            [...selector.searchResList].forEach(selector => selector.innerHTML = content);
        }
    }

    console.log(response)
}

function clearSearchResult() {
    if (selector.searchResList.length > 0)
        [...selector.searchResList].forEach(selector => selector.innerHTML = "");
}



function sendSearchRequestMb(name) {
    clearTimeout(state.debounceTimer);

    if (!name) {
        selector.searchContainerMb.style.display = "none";
        selector.searchContainerPc.style.display = "none";
        clearSearchResult();
        return;
    }

    selector.searchContainerMb.style.display = "block";
    selector.searchContainerPc.style.display = "block";

    if (selector.searchResList.length > 0) {
        [...selector.searchResList].forEach(selector => selector.innerHTML = `
                <li class="no-results">
                    Finding...
                </li>`
        );
    }

    state.debounceTimer = setTimeout(() => {
        $.ajax({
            url: `/api/search?name=${name}`,
            method: "GET",
            processData: false,
            contentType: false,
            success: function (response) {
                console.log(response);
                handleSuccessSearchRequest(response, name);
            },
            error: function (xhr, status, error) {
                console.log(JSON.parse(xhr.responseText));
                // handleErrorLogoutRequest(JSON.parse(xhr.responseText));
            }
        });
    }, 500);
}

function onChangeSearchInputMb(event) {
    sendSearchRequestMb(event.target.value)
}
//#endregion

function refreshNumInCart() {
    $.ajax({
        url: `/api/cart`,
        method: 'GET',
        success: function (response) {
            console.log(response);
            const totalQuantity = response.reduce((quantity, product) => {
                return quantity + product.quantity;
            }, 0);
            document.querySelector("#numInCart").innerHTML = `${totalQuantity}`;
        },
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
        }
    });
}



function subscribe(event) {
    event.preventDefault();

    const form = new FormData(event.target);
    $.ajax({
        url: `/api/subscribe`,
        method: 'POST',
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            openToast(response.message, true);
        },
        error: function (xhr, status, error) {
            // console.error("Request failed:",);
            const response = JSON.parse(xhr.responseText);
            switch (response.code) {
                case 425:
                    openToast(response.message, true);
                    break;
            }
        }
    });

}



//#region Profile Modal
function showProfileModal(event) {
    // Close Menu
    const dropdownMenu = document.querySelectorAll(".dropdown-menu")
    dropdownMenu.forEach(menu => menu.classList.remove("active"))

    $.ajax({
        url: `/api/user`,
        method: 'GET',
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            ModalTabManager.user = response.data;
            // Open modal
            const modal = profileModalContainer.querySelector(".modal");
            setTimeout(() => {
                modalTabManager.loadTabByState(ModalTabManager.state);
                modal.classList.add("show");
            }, 100);
        },
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
        }
    });
}

function closeProfileModal() {
    const modal = document.querySelector("#profileModal");
    modal.classList.remove("show");
}


function ModalTabManager() {
    this.tabContainer = document.querySelector(".modal-tabs");
    this.tabItems = document.querySelectorAll(".modal-tab-item");
    this.modalContent = document.querySelector("#profileModal .modal-content")
    ModalTabManager.state = "BASIC";
    ModalTabManager.user = {};
    this.tabItems.forEach(item => {
        item.addEventListener("click", (event) => {
            this.tabItems.forEach(item => item.classList.remove("selected"));
            event.target.classList.add("selected");
            const newState = event.target.dataset.state;
            if (ModalTabManager.state === newState) return;
            ModalTabManager.state = event.target.dataset.state;
            this.loadTabByState(newState);
        });
    });
    return this;
}

ModalTabManager.handleSubmit = function (event) {
    event.preventDefault();

    switch (ModalTabManager.state) {
        case "BASIC":
            FormAction.changeBasicInfo(event);
            break;
        case "AUTH":
            FormAction.authEmail(event);
            break;
        case "CHANGE":
            FormAction.changeEmail(event);
            break;
        case "PASSWORD":
            FormAction.changePassword(event);
            break;
        default:
    }
}

ModalTabManager.submitForm = function (event) {
    const form = $("#settingForm")[0];
    $(form).trigger("submit");
}

ModalTabManager.prototype.loadTabByState = function (state) {
    const user = ModalTabManager.user;
    const saveBtn = document.querySelector("#profileModalSaveBtn");
    switch (state) {
        case "BASIC":
            this.modalContent.innerHTML = `
                <form id="settingForm" onsubmit="ModalTabManager.handleSubmit(event)">
                    <div class="input-group mb-4">
                        <label for="firstnameInput">Firstname</label>
                        <input type="text" id="firstnameInput" class="input" placeholder="Firstname"
                            name="firstname" value="${user.firstName}">
                        <div id="firstnameFeedback" class="invalid-feedback"></div>
                    </div>
                    <div class="input-group mb-4">
                        <label for="lastnameInput">Lastname</label>
                        <input type="text" id="lastnameInput" class="input" placeholder="Lastname"
                            name="lastname" value="${user.lastName}">
                        <div id="lastnameFeedback" class="invalid-feedback"></div>
                    </div>
                    <div class="input-group mb-4">
                        <label for="addressInput">Address</label>
                        <input type="text" id="addressInput" class="input" placeholder="Addresss"
                            name="address" value="${user.address ?? ""}">
                        <div id="addressFeedback" class="invalid-feedback"></div>
                    </div>
                </form>
            `;
            saveBtn.innerHTML = "Save";
            break;

        case "AUTH":
            this.modalContent.innerHTML = `
                <form id="settingForm" onsubmit="ModalTabManager.handleSubmit(event)">
                    <div class="input-group mb-4">
                        <label for="emailInput">Email</label>
                        <input type="email" id="emailInput" class="input disabled" placeholder="Email" value="${user.email}" disabled>
                        <div id="emailFeedback" class="invalid-feedback"></div>
                    </div>
                </form>
            `;
            saveBtn.innerHTML = "Auth";
            break;
        case "CHANGE":
            this.modalContent.innerHTML = `
                <form id="settingForm" onsubmit="ModalTabManager.handleSubmit(event)">
                    <div class="input-group mb-4">
                        <label for="emailInput" style="margin-bottom: 8px;">Email (if you put an different email, you have to auth your email again!)</label>
                        <input type="email" id="emailInput" class="input" placeholder="Email"
                            name="email" value="${user.email}">
                        <div id="emailFeedback" class="invalid-feedback"></div>
                    </div>
                </form>
            `;
            saveBtn.innerHTML = "Change";
            break;

        case "PASSWORD":
            this.modalContent.innerHTML = `
                <form id="settingForm" onsubmit="ModalTabManager.handleSubmit(event)">
                    <div class="input-group mb-4">
                        <label for="currentPassword">Current password</label>
                        <input type="password" id="currentPasswordInput" class="input" placeholder="Current password"
                            name="currentPassword" autocomplete="none">
                        <div id="currentPasswordFeedback" class="invalid-feedback"></div>
                    </div>

                    <div class="input-group mb-4">
                        <label for="newPassword">New password</label>
                        <input type="password" id="newPasswordInput" class="input" placeholder="New password"
                            name="newPassword" autocomplete="none">
                        <div id="newPasswordFeedback" class="invalid-feedback"></div>
                    </div>
                </form>
            `;
            saveBtn.innerHTML = "Change";
            break;
        default:
    }
}


function FormAction() {
    return this;
}

FormAction.changeBasicInfo = function (event) {
    const form = new FormData(event.target);

    form.entries().forEach(pair => console.log(pair));
    $.ajax({
        url: `/api/user/change-basic-info`,
        method: 'POST',
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            // Open modal
            closeProfileModal();
            openToast(response.message);
        },
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            const errors = response.errors;

            const content = Object.keys(errors).reduce((content, key) => {
                return content +
                    `
                    ${key}: 
                    ${errors[key].join(" - ")}<br>
                    `
            }, "");

            openToast(content, true);
        }
    });

}

FormAction.authEmail = function (event) {
    $.ajax({
        url: `/api/user/auth-email`,
        method: 'POST',
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            // Open modal
            closeProfileModal();
            openToast(response.message);
        },
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            openToast(response.message, true);
        }
    });
}

FormAction.changeEmail = function (event) {
    const form = new FormData(event.target);

    for (const [key, value] of form.entries()) {
        if (key === "email" && value === ModalTabManager.user.email) {
            closeProfileModal();
            openToast("No change!");
            return;
        }
    }

    $.ajax({
        url: `/api/user/change-email`,
        method: 'POST',
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            // Open modal
            closeProfileModal();
            openToast(response.message);
        },
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            const errors = response.errors;

            const content = Object.keys(errors).reduce((content, key) => {
                return content +
                    `
                    ${key}: 
                    ${errors[key].join(" - ")}<br>
                    `
            }, "");

            openToast(content, true);
        }
    });
}

FormAction.changePassword = function (event) {
    const form = new FormData(event.target);

    form.entries().forEach(pair => console.log(pair));
    $.ajax({
        url: `/api/user/change-password`,
        method: 'POST',
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            // Open modal
            closeProfileModal();
            openToast(response.message);
        },
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            const errors = response.errors;

            const content = Object.keys(errors).reduce((content, key) => {
                return content +
                    `
                    ${key}: 
                    ${errors[key].join(" - ")}<br>
                    `
            }, "");

            openToast(content, true);
        }
    });
}

//#endregion