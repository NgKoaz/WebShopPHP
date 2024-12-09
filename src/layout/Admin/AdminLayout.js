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
    Sidebar.gI().init();
    Toast.gI().init();
    Modal.gI().init();

    setClickDropdown();

    const logoutButton = document.querySelector("[data-logout-btn]");
    logoutButton.onclick = sendLogoutRequest;
})


function Topbar() {

}


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


function Utility() { }
Utility.shortString = function (inputStr, maxLen) {
    return inputStr.length > maxLen ? inputStr.slice(0, maxLen) + "..." : inputStr;
}


function Sidebar() {
    Sidebar.instance = null;
    this.isOpen = true;
    this.toggleNavBtn = "#toggleNavBtn";
    this.sidebar = "aside.left"
}

Sidebar.gI = function () {
    if (!Sidebar.instance) Sidebar.instance = new Sidebar();
    return Sidebar.instance;
}

Sidebar.prototype.init = function () {
    this.setActive();
    this.toggleNavBtn = document.querySelector(this.toggleNavBtn);
    this.sidebar = document.querySelector(this.sidebar);
    this.toggleNavBtn.onclick = function () {
        this.toggle();
    }.bind(this);
}

Sidebar.prototype.toggle = function () {
    this.isOpen = !this.isOpen;
    // console.log(this);
    if (this.isOpen) {
        this.sidebar.classList.add("show");
        this.toggleNavBtn.innerHTML = `<i class="bi bi-arrow-left-circle-fill active"></i>`;
    } else {
        this.sidebar.classList.remove("show");
        this.toggleNavBtn.innerHTML = `<i class="bi bi-arrow-right-circle-fill active"></i>`;
    }
}

Sidebar.prototype.setActive = function () {
    const path = window.location.pathname;
    const links = document.querySelectorAll("ul.my-navbar li a");
    links.forEach(link => {
        if (link.getAttribute("href") === path) {
            link.classList.add("active");
        }
    })
}




function Color() { }
Color.Red = "#ff3838";
Color.Green = "#32ff7e";
Color.DarkGreen = "#3ae374";


function Toast() {
    Toast.instance = null;
    Toast.isInit = false;
    this.toastLive = "#liveToast";
    this.toastBootstrap = null;
    this.toastTitle = ".toast .toast-title";
    this.toastBody = ".toast .toast-body";
    this.toastRect = ".toast rect";
}

Toast.gI = function () {
    if (!Toast.instance) Toast.instance = new Toast();
    Toast.instance.init();
    return Toast.instance;
}

Toast.prototype.init = function () {
    if (Toast.isInit) return;

    this.toastLive = document.querySelector(this.toastLive);
    this.toastBootstrap = bootstrap.Toast.getOrCreateInstance(this.toastLive);
    this.toastTitle = document.querySelector(this.toastTitle);
    this.toastBody = document.querySelector(this.toastBody);
    this.toastRect = document.querySelector(this.toastRect);

    const blacklist = ["toastBootstrap"];
    Toast.isInit = !Object.entries(this).some(([key, value]) => !blacklist.some(k => k == key) && value == null);
}

Toast.prototype.showSuccess = function (message) {
    this.toastRect.setAttribute('fill', Color.Green);
    this.toastTitle.innerHTML = "Success!";
    this.toastTitle.style.color = Color.Green;
    this.toastBody.innerHTML = message;
    this.toastBootstrap.show();
}

Toast.prototype.showError = function (message) {
    this.toastRect.setAttribute('fill', Color.Red);
    this.toastTitle.innerHTML = "Error!";
    this.toastTitle.style.color = Color.Red;
    this.toastBody.innerHTML = message;
    this.toastBootstrap.show();
}




function Modal() {
    Modal.instance = null;
    Modal.isInit = false;
    this.modal = "#modal";
    this.modalLabel = "#modalLabel";
    this.closeBtn = "#closeModalButton";
    this.submitBtn = "#submitModalButton";
    this.modalBody = ".modal-body";
    return this;
}

Modal.gI = function () {
    if (!Modal.instance) Modal.instance = new Modal();
    Modal.instance.init();
    return Modal.instance;
}

Modal.prototype.init = function () {
    if (Modal.isInit) return;

    this.modal = document.querySelector(this.modal);
    this.modalLabel = document.querySelector(this.modalLabel);
    this.closeBtn = document.querySelector(this.closeBtn);
    this.submitBtn = document.querySelector(this.submitBtn);
    this.modalBody = modal.querySelector(this.modalBody);

    const blacklist = [""];
    Modal.isInit = !Object.entries(this).some(([key, value]) => !blacklist.some(k => k == key) && value == null);
}

Modal.prototype.show = function (title, body, isShowSubmitBtn, submitBtnText, typeBtnClass, onSubmitBtnClick, onLoadData) {
    this.modalLabel.innerHTML = title;
    this.modalBody.innerHTML = body;

    this.submitBtn.classList.remove("btn-primary");
    this.submitBtn.classList.remove("btn-danger");
    this.submitBtn.classList.remove("btn-success");
    this.submitBtn.style.display = "";
    this.submitBtn.innerHTML = submitBtnText;
    this.submitBtn.style.display = isShowSubmitBtn ? "" : "none";
    if (typeBtnClass) this.submitBtn.classList.add(typeBtnClass);
    if (onSubmitBtnClick) this.submitBtn.onclick = onSubmitBtnClick;
    if (onLoadData) onLoadData();
}

Modal.prototype.close = function () {
    this.closeBtn.click();
}



