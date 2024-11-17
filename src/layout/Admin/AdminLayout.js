function setActiveForNav() {
    const path = window.location.pathname;
    const links = document.querySelectorAll("ul.my-navbar li a");
    links.forEach(link => {
        if (link.getAttribute("href") === path) {
            link.classList.add("active");
        }
    })
}

function onStart() {
    setActiveForNav()
}



onStart()