const styleOptions = document.querySelectorAll(".style-selection li");

const minPrice = document.querySelector("#minPrice");
const maxPrice = document.querySelector("#maxPrice");

const filterModal = document.querySelector("#filterModal");

const cards = document.querySelectorAll(".card");
let numCardInline = 3;
let isLargeScreen = true;


document.addEventListener("DOMContentLoaded", () => {
    minPrice.addEventListener("mousedown", (event) => {
        event.preventDefault();
    });

    maxPrice.addEventListener("mousedown", (event) => {
        event.preventDefault();
    });


    [...styleOptions].forEach(option => {
        option.addEventListener("click", (event) => {
            event.target.querySelector("input")?.click();
        })
    });


    handleResize();
});

window.addEventListener("resize", handleResize);

function handleResize() {
    const width = window.innerWidth;
    if (width >= 768) {
        numCardInline = 3;
        isLargeScreen = true;
    } else {
        isLargeScreen = false;
        numCardInline = 2;
    }

    [...cards].forEach(card => {
        card.style.width = `${100 / numCardInline}%`;
    });
    handleFilterMobile();
}

function handleFilterMobile() {
    if (!isLargeScreen) return;
    closeFilterModal(null);
}


function filterProducts(event) {
    const categoryRadios = document.querySelectorAll("input[type='radio'][name='category']");
    const checkedRadio = [...categoryRadios].filter(r => r.checked);

    let url = `http://localhost:8080/categories`;
    if (checkedRadio.length > 0) {
        const categorySlug = checkedRadio[0].value;
        url += `/${categorySlug}`;
    }

    const priceOptions = {
        price: {
            min: minPrice.value,
            max: maxPrice.value
        }
    }

    url += `?options=${encodeURIComponent(JSON.stringify(priceOptions))}`

    window.location.href = url;
}

function closeFilterModal(event) {
    filterModal.classList.remove("show");
    const overlays = document.querySelectorAll(".overlay");
    if (overlays.length) [...overlays].forEach(ol => ol.remove());
}


function showFilterMobile(event) {
    filterModal.classList.add("show");
    const overlay = document.createElement("div");
    overlay.className = "overlay";
    filterModal.insertAdjacentElement("afterend", overlay);
}



