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


const documentReadyCallback = [updateResizing];

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


function updateResizing() {
    footerContainer.style.marginTop = (-subscription.offsetHeight / 2 + 32) + "px";
    const indentValue = (window.innerWidth - 1240) / 2;
    if (window.innerWidth > 1240) {
        document.documentElement.style.setProperty('--indent-default', `${indentValue}px`);
        selector.searchFormMb.style.display = "none";
    }
    else {
        selector.searchContainerMb.style.display = "none";
        selector.searchFormMb.style.display = "block";
        selector.searchContainerPc.style.display = "none";
        document.documentElement.style.removeProperty('--indent-default')
    }
}

document.onreadystatechange = () => {
    documentReadyCallback.forEach(cb => cb())
};
window.addEventListener('resize', updateResizing);




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


//#endregion



//#region Search Request
function handleSuccessSearchRequest(response) {
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
        return content +
            `
            <li class="itemResult">
                <a class="itemLink" href="/products/${product.slug}">
                    <div class="image">
                        <img src="/public/images/cart/p1.png">
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
                    <button id="moreResultsBtn">More ${moreNum} results</button>
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
                handleSuccessSearchRequest(response);
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


function onClickNavLink(event, number) {
    event.preventDefault();
    switch (number) {
        case 0:
            window.location.href = "/categories?options=%7B%0A%22order%22%3A%20%22createdAt%22%0A%7D";
            break;
        case 1:
            window.location.href = "/categories?option=topSelling";
            break;
        default:
    }
}

