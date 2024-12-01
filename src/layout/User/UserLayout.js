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
let toastTimer = null;


document.addEventListener("DOMContentLoaded", () => {
    refreshNumInCart();
})

document.onclick = (event) => {
    closeSearchWhenClickOutside(event);
    closeDropdownWhenClickOutside(event);
}

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



function openToast(message) {
    selector.toastContainer.style.pointerEvents = "all";
    selector.toastContainer.innerHTML = `
    <div id="toast" class="toast">
        <div class="toast-icon"><i class="bi bi-check-circle-fill"></i></div>
        <div class="toast-content">
            <div class="toast-title">Success!</div>
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
    dropdown.querySelector(".dropdown-menu").classList.toggle("active");
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


