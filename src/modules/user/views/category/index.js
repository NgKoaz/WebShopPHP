const styleOptions = document.querySelectorAll(".style-selection li");

const filterModal = document.querySelector("#filterModal");

const cards = document.querySelectorAll(".card");
let numCardInline = 3;
let isLargeScreen = true;





document.addEventListener("DOMContentLoaded", () => {
    bindSliderEvent();

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


function filterProducts(event, isPC) {
    const categoryRadios = document.querySelectorAll("input[type='radio'][name='category']");
    const checkedRadio = [...categoryRadios].filter(r => r.checked);

    let url = `http://localhost:8080/categories`;
    if (checkedRadio.length > 0) {
        const categorySlug = checkedRadio[0].value;
        url += `/${categorySlug}`;
    }

    let minValue = 0;
    let maxValue = 0;
    let priceSlider = isPC ?
        document.querySelector("[data-device='PC']") :
        document.querySelector("[data-device='Mobile']");

    minValue = priceSlider.querySelector(".min-price-input").value;
    maxValue = priceSlider.querySelector(".max-price-input").value;

    const priceOptions = {
        price: {
            min: minValue,
            max: maxValue
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


function bindSliderEvent() {
    const url = window.location.href;
    const urlParams = new URLSearchParams(new URL(url).search);
    const optionsParam = urlParams.get("options") ?? "[]";
    const priceOptions = JSON.parse(optionsParam);

    const priceSlider = document.querySelectorAll(".price:has(.price-slider)");
    priceSlider.forEach(slider => {
        const sliderRange = slider.querySelector(".slider-range");
        const minTooltip = slider.querySelector(".min-tooltip");
        const maxTooltip = slider.querySelector(".max-tooltip");
        const minPriceSlider = slider.querySelector(".min-price-slider");
        const maxPriceSlider = slider.querySelector(".max-price-slider");
        const minPriceInput = slider.querySelector(".min-price-input");
        const maxPriceInput = slider.querySelector(".max-price-input");
        const MinValue = minPriceSlider.min;
        const MaxValue = minPriceSlider.max;
        const MinGap = 15;
        let minTooltipTimeout = null;
        let maxTooltipTimeout = null;


        const initialMinValue = priceOptions.price?.min ?? MinValue;
        const initialMaxValue = priceOptions.price?.max ?? MaxValue;
        minPriceSlider.value = initialMinValue;
        minPriceInput.value = initialMinValue;
        maxPriceSlider.value = initialMaxValue;
        maxPriceInput.value = initialMaxValue;

        sliderRange.style.left = `${initialMinValue / MaxValue * 100}%`;
        sliderRange.style.right = `${100 - initialMaxValue / MaxValue * 100}%`;



        const sanitizeValue = (val) => {
            val = +val;
            if (val < MinValue)
                return MinValue;
            if (val > MaxValue)
                return MaxValue;
            return val;
        };

        minPriceInput.addEventListener("change", (event) => {
            let inputValue = sanitizeValue(event.target.value);
            if (maxPriceInput.value - minPriceInput.value <= MinGap) {
                inputValue = +maxPriceSlider.value - MinGap;
            }
            event.target.value = inputValue;
            minTooltip.innerHTML = `$${inputValue}`;
            minTooltip.style.left = `${inputValue / MaxValue * 100}%`;
            sliderRange.style.left = `${inputValue / MaxValue * 100}%`;
            minPriceSlider.value = inputValue;
        });

        maxPriceInput.addEventListener("change", (event) => {
            let inputValue = sanitizeValue(event.target.value);
            if (maxPriceInput.value - minPriceInput.value <= MinGap) {
                inputValue = +minPriceSlider.value + MinGap;
            }
            event.target.value = inputValue;
            maxTooltip.innerHTML = `$${inputValue}`;
            maxTooltip.style.left = `${inputValue / MaxValue * 100}%`;
            sliderRange.style.right = `${100 - inputValue / MaxValue * 100}%`;
            maxPriceSlider.value = inputValue;
        });

        minPriceSlider.addEventListener("input", (event) => {
            minTooltip.classList.remove("hide");
            clearTimeout(minTooltipTimeout);
            minTooltipTimeout = setTimeout(() => {
                minTooltip.classList.add("hide");
            }, 500);

            let value = sanitizeValue(event.target.value);
            if (maxPriceSlider.value - minPriceSlider.value <= MinGap) {
                value = +maxPriceSlider.value - MinGap;
                event.target.value = value;
            }
            minPriceInput.value = value;

            minTooltip.innerHTML = `$${value}`;
            minTooltip.style.left = `${value / MaxValue * 100}%`;
            sliderRange.style.left = `${value / MaxValue * 100}%`;
        });


        maxPriceSlider.addEventListener("input", (event) => {
            maxTooltip.classList.remove("hide");
            clearTimeout(maxTooltipTimeout);
            maxTooltipTimeout = setTimeout(() => {
                maxTooltip.classList.add("hide");
            }, 500);

            let value = sanitizeValue(event.target.value);
            if (maxPriceSlider.value - minPriceSlider.value <= MinGap) {
                value = +minPriceSlider.value + MinGap;
                event.target.value = value;
            }
            maxPriceInput.value = value;

            maxTooltip.innerHTML = `$${value}`;
            maxTooltip.style.left = `${value / MaxValue * 100}%`;
            sliderRange.style.right = `${100 - value / MaxValue * 100}%`;

        });
    });
}


