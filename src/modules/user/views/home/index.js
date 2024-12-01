const newArrivalCardList = document.querySelector(".new-arrival .card-list");
const newTopSellingList = document.querySelector(".top-selling .card-list");

const prev = document.querySelector("#prevTestimonial");
const next = document.querySelector("#nextTestimonial");

const cardsContainer = document.querySelector(".testimonial-cards");
const cards = document.querySelectorAll(".testimonial-card-container");

let numberOfComment = cards.length;
let numCardDisplay = 3;
let startIndex = numCardDisplay;
let endIndex = numberOfComment - numCardDisplay * 2;
let curCardIndex = 3;

document.addEventListener('DOMContentLoaded', () => {
    refreshNewArrivalList();
    refreshTopSellingList();

    handleResize();

    reloadTestiCard();
});

window.addEventListener("resize", handleResize)

function handleResize(event) {
    const width = window.innerWidth;
    if (width >= 1240) {
        numCardDisplay = 3;
        endIndex = numberOfComment - numCardDisplay * 2;
    } else if (width >= 768) {
        numCardDisplay = 2;
        endIndex = numberOfComment - (3 - numCardDisplay) * 3 - 3;
    } else {
        numCardDisplay = 1;
        endIndex = numberOfComment - (3 - numCardDisplay) * 3;
    }

    startIndex = (3 - numCardDisplay) * 2 + numCardDisplay;
    curCardIndex = 3;

    cardsContainer.style.width = `${100 * numberOfComment / numCardDisplay}%`;
}

function refreshNewArrivalList() {
    $.ajax({
        url: `/api/products?limit=4&options=%7B%0A%22order%22%3A%20%22created_at%22%0A%7D`,
        method: "GET",
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            const products = response.products;
            const content = products.reduce((content, product) => {
                const numStar = (product.total_reviews) ? Math.round(product.total_rates / product.total_reviews * 10 / 20) / 10 : 0;
                const fillStar = Math.floor(numStar);
                const isHalf = (Math.round(numStar * 10) % 10 == 0 ? 0 : 1);
                const noFillStar = 5 - fillStar - isHalf;

                const images = JSON.parse(product.images ?? "[]");

                return content + `
                <a class="card" href="/products/${product.slug}">
                    <img src="${(images.length !== 0) ? images[0]["lg"] : "/public/images/no_image.webp"}">
                    <h3 class="title">${product.name}</h3>
                    <div class="stars">
                        ${`<i class="bi bi-star-fill star-ic"></i> `.repeat(fillStar)}
                        ${`<i class="bi bi-star-half star-ic"></i> `.repeat(isHalf)}
                        ${`<i class="bi bi-star star-ic"></i> `.repeat(noFillStar)}
                        <span>${numStar}/5</span>
                    </div>
                    <div class="price">
                        $${product.price}
                    </div>
                </a>`;
            }, "");
            newArrivalCardList.innerHTML = content;
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

function refreshTopSellingList() {
    $.ajax({
        url: `/api/products?limit=4&options=%7B%0A%22order%22%3A%20%22sold_number%22%0A%7D`,
        method: "GET",
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            const products = response.products;

            const content = products.reduce((content, product) => {
                const numStar = (product.total_reviews) ? Math.round(product.total_rates / product.total_reviews * 10 / 20) / 10 : 0;
                const fillStar = Math.floor(numStar);
                const isHalf = (Math.round(numStar * 10) % 10 == 0 ? 0 : 1);
                const noFillStar = 5 - fillStar - isHalf;

                const images = JSON.parse(product.images ?? "[]");

                return content + `
                <a class="card" href="/products/${product.slug}">
                    <img src="${(images.length !== 0) ? images[0]["lg"] : "/public/images/no_image.webp"}">
                    <h3 class="title">${product.name}</h3>
                    <div class="stars">
                        ${`<i class="bi bi-star-fill star-ic"></i> `.repeat(fillStar)}
                        ${`<i class="bi bi-star-half star-ic"></i> `.repeat(isHalf)}
                        ${`<i class="bi bi-star star-ic"></i> `.repeat(noFillStar)}
                        <span>${numStar}/5</span>
                    </div>
                    <div class="price">
                        $${product.price}
                    </div>
                </a>`;
            }, "");
            newTopSellingList.innerHTML = content;
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}


prev.addEventListener("click", (event) => {
    curCardIndex--;
    reloadTestiCard(true);
});

next.addEventListener("click", (event) => {
    curCardIndex++;
    reloadTestiCard(true);
});


cardsContainer.addEventListener('transitionend', () => {
    const isTouchCeil = (curCardIndex >= (numberOfComment - numCardDisplay));
    const isTouchFloor = curCardIndex <= 0;
    if (!isTouchCeil && !isTouchFloor) return;
    curCardIndex = (isTouchCeil) ? startIndex : endIndex;
    reloadTestiCard(false);
});


function reloadTestiCard(isSmooth) {
    cardsContainer.style.transition = isSmooth ? "" : "none";
    cardsContainer.style.transform = `translateX(-${100 / numberOfComment * curCardIndex}%)`;
    [...cards].forEach((card, index) => {
        const isDisplay = curCardIndex <= index && index < curCardIndex + numCardDisplay;
        card.style.transition = isSmooth ? "" : "none";
        card.style.filter = isDisplay ? "" : "blur(1.5px)";
        card.style.opacity = isDisplay ? "" : "0.2";
    });
}

