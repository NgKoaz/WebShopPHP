const orderManager = new OrderManager();
const tabManager = new TabManager();

document.addEventListener("DOMContentLoaded", () => {
    orderManager.fetchOrders(() => {
        tabManager.reloadCurrentTab();
    });
});

function OrderManager() {
    this.tbodyOrderTable = document.querySelector("#orderTable tbody");
    OrderManager.orders = [];
    return this;
}

OrderManager.prototype.fetchOrders = function (onDone) {
    $.ajax({
        url: `/api/orders`,
        method: 'GET',
        success: function (response) {
            console.log(response);
            OrderManager.orders = response;
            if (onDone) onDone();
        },
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
        }
    });
}

OrderManager.prototype.loadOrder = function () {

}

OrderManager.prototype.loadTableRows = function (orders) {
    console.log(orders);
    const content = orders.reverse(order => order.created_at).reduce((content, order) => {
        return content +
            `<tr>
                <td>${order.id}</td>
                <td>${order.created_at}</td>
                <td>${order.order_status}</td>
                <td>${order.status}</td>
                <td>$${order.total_price}</td>
                <td>
                    <button>Detail</button>
                    <form method="POST" action="/order-rebuy">
                        <input type="hidden" name="billId" value=${order.id}> 
                        <button>Rebuy</button>
                    </form>
                    <form method="POST" action="/order-pay">
                        <input type="hidden" name="billId" value=${order.id}> 
                        <button>Pay</button>
                    </form>
                    <button>Review</button>
                    <form onsubmit="orderManager.cancelOrder(event)">
                        <input type="hidden" name="billId" value=${order.id}> 
                        <button>Cancel</button>
                    </form>
                </td>
            </tr>`
    }, "");

    this.tbodyOrderTable.innerHTML = content;
}

OrderManager.prototype.cancelOrder = function (event) {
    event.preventDefault();

    const form = new FormData(event.target);
    $.ajax({
        url: `/api/orders/cancel`,
        method: 'POST',
        data: form,
        contentType: false,
        processData: false,
        success: function (response) {
            console.log(response);
            this.fetchOrders(() => {
                tabManager.reloadCurrentTab();
            });
        }.bind(this),
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
        }
    });
}



function TabManager() {
    this.tabContainer = document.querySelector(".tab-slider");
    this.tabItems = document.querySelectorAll(".tab-item");
    this.cardList = document.querySelector(".card-list");
    this.tabItems.forEach(item => {
        item.addEventListener("click", (event) => {
            this.tabItems.forEach(item => item.classList.remove("selected"));
            event.target.classList.add("selected");
            const newState = event.target.dataset.state;
            if (this.tabContainer.dataset.state === newState) return;
            this.tabContainer.dataset.state = newState;
            this.setTabState(newState);
        });
    });

    return this;
}

TabManager.prototype.reloadCurrentTab = function () {
    this.setTabState(this.tabContainer.dataset.state);
}

TabManager.prototype.setTabState = function (state) {
    switch (state) {
        case "UNPAID":
            this.loadOrderWithOrderList(
                OrderManager.orders.filter(order => order.status === "UNPAID"),
                "Unpaid",
                `<button class="card-btn card-btn-secondary" onclick="OrderActions.cancel(event)">Cancel</button>
                <button class="card-btn card-btn-primary">Pay</button>`
            );
            break;
        case "PREPARING":
            this.loadOrderWithOrderList(
                OrderManager.orders.filter(order => order.order_status === "PREPARING"),
                "Preparing",
                `<button class="card-btn card-btn-secondary" onclick="OrderActions.cancel(event)">Cancel</button>`
                    + order.status === "UNPAID" ? `<button class="card-btn card-btn-primary">Pay</button>` :
                    `<button class="card-btn card-btn-secondary">Detail</button>`
            );
            break;
        case "SHIPPING":
            this.loadOrderWithOrderList(
                OrderManager.orders.filter(order => order.order_status === "SHIPPING"),
                "Shipping",
                `<button class="card-btn card-btn-secondary">Detail</button>`
            );
            break;
        case "SHIPPED":
            this.loadOrderWithOrderList(
                OrderManager.orders.filter(order => order.order_status === "SHIPPED"),
                "Shipped",
                `<button class="card-btn card-btn-secondary">Detail</button>
                <button class="card-btn card-btn-primary">Received</button>`
            );
            break;
        case "RECEIVED":
            this.loadOrderWithOrderList(
                OrderManager.orders.filter(order => order.order_status === "RECEIVED"),
                "Received",
                `<button class="card-btn card-btn-secondary">Rebuy</button>
                <button class="card-btn card-btn-primary">Review</button>`
            );
            break;
        case "CANCELLED":
            this.loadOrderWithOrderList(
                OrderManager.orders.filter(order => order.status === "CANCELLED"),
                "Cancelled",
                `<button class="card-btn card-btn-primary">Rebuy</button>`
            );
            break;
        default:
    }
}

TabManager.prototype.loadOrderWithOrderList = function (orders, status, actionHTML) {
    const content = orders.reduce((content, order) => {
        return content +
            `<div class="card-container">
                <div class="card">
                    <div class="card-top">
                        <div class="shop-name">BK.CO</div>
                        <div class="status">${status}</div>
                    </div>
                    <div class="product-container">
                        <div class="product-list">
                            <div class="product">
                                <img src="/public/upload/images/1674ae6152c9031732961813.png">
                                <div class="product-info">
                                    <div class="product-title">Gradient Graphic T-shirt</div>
                                    <div class="product-quantity">x1</div>
                                    <div class="product-price">$25.99</div>
                                </div>
                            </div>
                        </div>
                        <button class="card-more-btn">More <i class="bi bi-arrow-bar-down"></i></button>
                    </div>
                    <div class="total-price">Total price (1 product): $12312312</div>
                    <div class="actions" data-bill-id="${order.id}">
                        ${actionHTML}
                    </div>
                </div>
            </div>`
    }, "");
    this.cardList.innerHTML = content ? content : `<p style="font-size: var(--md-font-size); text-align: center; color: rgba(0,0,0,0.6); flex-grow: 1;">Empty!</p>`;
}


function OrderActions() { }

OrderActions.cancel = function (event) {
    // console.log(event);
    const form = new FormData();
    form.append("billId", event.target.closest(".actions").dataset.billId);
    $.ajax({
        url: `/api/orders/cancel`,
        method: 'POST',
        data: form,
        contentType: false,
        processData: false,
        success: function (response) {
            console.log(response);
            orderManager.fetchOrders(() => {
                tabManager.reloadCurrentTab();
            });
        }.bind(this),
        error: function (xhr, status, error) {
            console.error("Request failed:", xhr.responseText);
        }
    });
}

OrderActions.pay = function () {

}

OrderActions.receive = function () {

}




// `<div class="card-container">
//             <div class="card">
//                 <div class="product-container">
//                     <div class="product-list">
//                         <div class="product">
//                             <img src="/public/upload/images/1674ae6152c9031732961813.png">
//                             <div class="product-info">
//                                 <div class="product-title">Gradient Graphic T-shirt</div>
//                                 <div class="product-quantity">x1</div>
//                                 <div class="product-price">$25.99</div>
//                             </div>
//                         </div>
//                     </div>
//                     <button class="card-more-btn">More <i class="bi bi-arrow-bar-down"></i></button>
//                 </div>
//                 <div class="total-price">Total price (1 product): $12312312</div>
//                 <div class="actions">
//                     <button class="card-btn card-btn-primary">Received</button>
//                     <button class="card-btn">Cancel</button>
//                 </div>
//             </div>
//         </div>`
