const subscription = document.querySelector(".subscription");
const footerContainer = document.querySelector(".footer-container");

const updateResizing = () => {
    footerContainer.style.marginTop = (-subscription.offsetHeight / 2 + 32) + "px";
    const indentValue = (window.innerWidth - 1240) / 2;
    if (window.innerWidth > 1240)
        document.documentElement.style.setProperty('--indent-default', `${indentValue}px`);
    else document.documentElement.style.removeProperty('--indent-default')
}

updateResizing();
window.addEventListener('resize', updateResizing);