const mainContent = document.querySelector(".main-content");
state.items = [];
state.isLoadedTemplate = false;

document.addEventListener("DOMContentLoaded", () => {
    refreshCart();
});

{/* <div class="sm-entry">
                    <div>Discount (-20%)</div>
                    <div id="discount">-$113</div>
                </div> 
                <div id="deliveryFeeField" class="sm-entry">
                    <div>Delivery Fee</div>
                    <div id="deliveryFee">$15</div>
                </div>
                */}

function loadTemplate() {
    mainContent.innerHTML = `
    <div>
        <div class="items"></div>
    </div>
    <div>
        <form class="order-summary" action="/checkout" method="GET">
            <h4 class="title">Order Summary</h4>

            <div class="bill-details">
                <div id="subtotalField" class="sm-entry">
                    <div>Subtotal</div>
                    <div id="subtotal">$123</div>
                </div>           
                
            </div>

            <div id="totalField" class="lg-entry">
                <div>Total</div>
                <div id="total">$467</div>
            </div>

            <div class="promotion-code">
                <div class="input-container">
                    <i class="bi bi-tag"></i>
                    <input type="text" placeholder="Add promo code">
                </div>
                <button id="promoCodeBtn">Apply</button>
            </div>

            <button id="checkoutBtn" class="checkout-btn">
                Go to Checkout <i class="bi bi-chevron-double-right"></i>
            </button>
        </form>
    </div>`
}

function clearTemplate() {
    mainContent.innerHTML = `<b class="no-item-notify">No any items in your cart.</b>`;
}


function loadItems(response) {
    if (response.length > 0) {
        state.items = response;

        if (!state.isLoadedTemplate)
            loadTemplate();
        state.isLoadedTemplate = true;
    }
    else {
        state.items = [];
        clearTemplate();
        state.isLoadedTemplate = false;
    }

    const content = response.reduce((content, obj) => {
        const product = obj.product;
        const quantity = obj.quantity;

        const images = JSON.parse(product.images ?? "[]");

        return content + `
                <div class="item">
                    <div class="left">
                        <img src="${images.length > 0 ? images[0]["sm"] : "/public/images/sm_no_image.webp"}">
                    </div>

                    <div class="right">
                        <div class="top">
                            <div class="info">
                                <div class="name">${product.name}</div>
                            </div>

                            <div class="delele-icon-container">
                                <i class="bi bi-trash-fill" onclick="deleteItem('${product.id}')"></i>
                            </div>
                        </div>

                        <div class="bottom">
                            <div class="price">$${product.price}</div>

                            <div class="quantity-modifier">
                                ${quantity}
                                <i class="bi bi-dash-lg minus" onclick="changeQuantity('${product.id}', -1)"></i>
                                <i class="bi bi-plus-lg plus" onclick="changeQuantity('${product.id}', 1)"></i>
                            </div>
                        </div>
                    </div>
                </div> `
    }, "");

    const totalQuantity = response.reduce((quantity, product) => {
        return quantity + product.quantity;
    }, 0);
    document.querySelector("#numInCart").innerHTML = `${totalQuantity}`;


    const subtotal = Math.round(response.reduce((totalPrice, obj) => {
        return totalPrice + +obj.product.price * +obj.quantity;
    }, 0) * 100) / 100;
    const subtotalText = document.querySelector("#subtotal");
    if (subtotalText) subtotalText.innerHTML = `$${subtotal}`;

    const totalText = document.querySelector("#total");
    if (totalText) totalText.innerHTML = `$${subtotal}`;


    const itemsContainer = document.querySelector(".items");
    if (itemsContainer) itemsContainer.innerHTML = content;
}


function refreshCart() {
    $.ajax({
        url: `/api/cart`,
        method: 'GET',
        success: function (response) {
            loadItems(response);
        },
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
        }
    });
}


function changeQuantity(productId, changeValue) {
    item = state.items.find(i => i.product.id === +productId);
    if (!item) return;

    const quantity = parseInt(item.quantity) + changeValue;
    if (quantity <= 0) return;

    form = new FormData()
    form.append("productId", productId);
    form.append("quantity", quantity);

    // console.log(productId, quantity);

    $.ajax({
        url: `/api/cart/edit`,
        method: 'POST',
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            loadItems(response);
        },
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
        }
    });
}


function deleteItem(productId) {
    form = new FormData();
    form.append("productId", productId);

    $.ajax({
        url: `/api/cart/delete`,
        method: 'POST',
        data: form,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            loadItems(response);
        },
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
        }
    });
}