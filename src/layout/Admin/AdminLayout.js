const dropdownMenuBtn = document.querySelectorAll(".dropdown-menu-btn");
const dropdownMenu = document.querySelectorAll(".dropdown .menu");
const documentOnClickCallback = {};



document.onclick = (event) => Object.values(documentOnClickCallback).forEach(c => c(event));


document.onreadystatechange = () => {
    setActiveForNav();
    setClickDropdown();

    const logoutButton = document.querySelector("[data-logout-btn]");
    logoutButton.onclick = sendLogoutRequest;
}

documentOnClickCallback["closeDropdown"] = (event) => {
    const dropdown = event.target.closest(".dropdown");
    if (!dropdown) {
        dropdownMenu.forEach(menu => menu.classList.remove("active"))
    }
};


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