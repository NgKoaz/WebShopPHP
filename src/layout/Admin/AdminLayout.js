const dropdownMenuBtn = document.querySelectorAll(".dropdown-menu-btn");
const dropdownMenu = document.querySelectorAll(".dropdown .menu");
const documentOnClickCallback = {};


document.onclick = (event) => Object.values(documentOnClickCallback).forEach(c => c(event));

documentOnClickCallback["closeDropdown"] = (event) => {
    const dropdown = event.target.closest(".dropdown");
    if (!dropdown) {
        dropdownMenu.forEach(menu => menu.classList.remove("active"))
    }
};

document.addEventListener("DOMContentLoaded", () => {
    Toast.gI().init();

    setActiveForNav();
    setClickDropdown();

    const logoutButton = document.querySelector("[data-logout-btn]");
    logoutButton.onclick = sendLogoutRequest;
})


function setClickDropdown() {
    dropdownMenuBtn.forEach(btn => {
        btn.onclick = onClickSetting
    })
}


function onClickSetting(event) {
    const dropdownMenu = event.target.nextElementSibling;
    dropdownMenu.classList.toggle("active");
}


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

function setActiveForNav() {
    const path = window.location.pathname;
    const links = document.querySelectorAll("ul.my-navbar li a");
    links.forEach(link => {
        if (link.getAttribute("href") === path) {
            link.classList.add("active");
        }
    })
}



function Color() { }
Color.red = "#ff3838";
Color.green = "#32ff7e";
Color.darkGreen = "#3ae374";


function Toast() {
    this.instance = null;
    this.toastLive = "#liveToast";
    this.toastBootstrap = null;
    this.toastTitle = ".toast .toast-title";
    this.toastBody = ".toast .toast-body";
    this.toastRect = ".toast rect";
}

Toast.gI = function () {
    if (this.instance) return this.instance;
    return this.instance = new Toast();
}

Toast.prototype.init = function () {
    this.toastLive = document.querySelector(this.toastLive);
    this.toastBootstrap = bootstrap.Toast.getOrCreateInstance(this.toastLive);
    this.toastTitle = document.querySelector(this.toastTitle);
    this.toastBody = document.querySelector(this.toastBody);
    this.toastRect = document.querySelector(this.toastRect);
}

Toast.prototype.showSuccess = function (message) {
    this.toastRect.setAttribute('fill', Color.green);
    this.toastTitle.innerHTML = "Success!";
    this.toastTitle.style.color = Color.green;
    this.toastBody.innerHTML = message;
    this.toastBootstrap.show();
}

Toast.prototype.showError = function (message) {
    this.toastRect.setAttribute('fill', Color.red);
    this.toastTitle.innerHTML = "Error!";
    this.toastTitle.style.color = Color.red;
    this.toastBody.innerHTML = message;
    this.toastBootstrap.show();
}


