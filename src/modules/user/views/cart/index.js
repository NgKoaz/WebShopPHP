const mainContent = document.querySelector(".main-content");
state.items = [];
state.isLoadedTemplate = false;

documentReadyCallback.push(() => {
    refreshCart();
});


function loadTemplate() {
    mainContent.innerHTML = `
    <div>
        <div class="items">
           
        </div>
    </div>
    <div>
        <div class="order-summary">
            <h4 class="title">Order Summary</h4>

            <div class="bill-details">
                <div class="sm-entry">
                    <div>Subtotal</div>
                    <div>$2839</div>
                </div>
                <div class="sm-entry">
                    <div>Discount (-20%)</div>
                    <div>-$113</div>
                </div>
                <div class="sm-entry">
                    <div>Delivery Fee</div>
                    <div>$15</div>
                </div>
            </div>

            <div class="lg-entry">
                <div>Total</div>
                <div>$467</div>
            </div>

            <div class="promotion-code">
                <div class="input-container">
                    <i class="bi bi-tag"></i>
                    <input type="text" placeholder="Add promo code">
                </div>
                <button>Apply</button>
            </div>

            <button class="checkout-btn">Go to Checkout <i class="bi bi-chevron-double-right"></i>
            </button>
        </div>
    </div>`
}

function clearTemplate() {
    mainContent.innerHTML = `<b class="no-item-notify">No any items in your cart.</b>`;
}


function loadItems(response) {
    if (response.length > 0) {
        if (!state.isLoadedTemplate)
            loadTemplate();
        state.isLoadedTemplate = true;
    }
    else {
        clearTemplate();
        state.isLoadedTemplate = false;
        return;
    }

    state.items = response;

    const content = response.reduce((content, obj) => {
        const product = obj.product;
        const quantity = obj.quantity;
        return content + `
                <div class="item">
                    <div class="left">
                        <img src="/public/images/cart/p1.png">
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

    const itemsContainer = document.querySelector(".items");
    itemsContainer.innerHTML = content;
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
            loadItems(response);
        },
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
        }
    });
}