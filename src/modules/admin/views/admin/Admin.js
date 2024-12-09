const earningChart = new Chart("EARNING", "#earningChart");
const userChart = new Chart("USER", "#userChart");
const orderChart = new Chart("ORDER", "#orderChart");

const newUserList = new ViewList("USER", "#newUserList");
const newOrderList = new ViewList("ORDER", "#newOrderList");


document.addEventListener("DOMContentLoaded", () => {
    MainPage.gI().init();
});


function MainPage() {
    MainPage.instance = null;
    this.earningCard = "[data-name='EARNING'] .custom-body";
    this.completedOrderCard = "[data-name='ORDER'] .custom-body";
    this.userCard = "[data-name='USER'] .custom-body";
    this.waitingOrderCard = "[data-name='WAITING'] .custom-body";

    return this;
}

MainPage.gI = function () {
    if (!MainPage.instance) MainPage.instance = new MainPage();
    return MainPage.instance;
}

MainPage.prototype.init = function () {
    this.earningCard = document.querySelector(this.earningCard);
    this.completedOrderCard = document.querySelector(this.completedOrderCard);
    this.userCard = document.querySelector(this.userCard);
    this.waitingOrderCard = document.querySelector(this.waitingOrderCard);

    this.updateUserOverview();
    this.updateOrderOverview();
}

MainPage.prototype.updateUserOverview = function () {
    this.earningCard;
    $.ajax({
        url: `/api/admin/overview/users`,
        method: "GET",
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            const users = response.data.users;
            this.userCard.innerHTML = users.length;
            const sortedUsers = users.sort((a, b) => new Date(a.created_at) <= new Date(b.created_at) ? -1 : 1);
            newUserList.setData(users.reverse());
            userChart.setData(sortedUsers);

            // userChart.setData(
        }.bind(this),
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

MainPage.prototype.updateOrderOverview = function () {
    this.earningCard;
    $.ajax({
        url: `/api/admin/overview/orders`,
        method: "GET",
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            const orders = response.data.orders;
            this.waitingOrderCard.innerHTML = orders.reduce((total, order) => total + (order.order_status === "PREPARING" ? 1 : 0), 0);
            this.completedOrderCard.innerHTML = orders.reduce((total, order) => total + (order.order_status === "SHIPPED" || order.order_status === "RECEIVED" ? 1 : 0), 0);
            this.earningCard.innerHTML = "$" + Math.round(orders.reduce((total, order) => total + +order.total_price, 0) * 100) / 100;
            newOrderList.setData(orders.filter(order => order.order_status === "PREPARING"))
            const sortedOrders = orders.sort((a, b) => new Date(a.created_at) <= new Date(b.created_at) ? -1 : 1);
            orderChart.setData(sortedOrders);
            earningChart.setData(sortedOrders.filter(order => order.status !== "UNPAID" && order.status !== "CANCELLED"));

        }.bind(this),
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}





function Chart(id, selector) {
    this.id = id;
    this.selector = selector;
    this.dataList = [];
    this.options = {
        chart: {
            height: 280,
            type: "area"
        },
        dataLabels: {
            enabled: false
        },
        responsive: [{
            breakpoint: undefined,
            options: {},
        }],
        fill: {
            type: "gradient",
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.9,
                stops: [0, 90, 100]
            }
        }
    };

    this.chart = null;
    return this;
}

Chart.prototype.setData = function (dataList) {
    this.dataList = dataList;
    let chartData = {};
    switch (this.id) {
        case "EARNING":
            chartData = this.dataList.reduce((chartData, data) => {
                const date = (new Date(data.created_at)).toDateString();
                if (chartData[date]) {
                    chartData[date] += +data.total_price;
                } else {
                    chartData[date] = +data.total_price;
                }
                return chartData;
            }, {});
            this.options.series = [
                {
                    name: "Amount $",
                    data: Object.values(chartData).map(v => (Math.round(v * 100) / 100))
                }
            ]
            this.options.xaxis = {
                categories: Object.keys(chartData).map(k => {
                    parts = k.split(" ");
                    return `${parts[1]} ${parts[2]} ${parts[3]}`;
                })
            }
            break;
        case "ORDER":
            chartData = this.dataList.reduce((chartData, data) => {
                const date = (new Date(data.created_at)).toDateString();
                if (chartData[date]) {
                    chartData[date]++;
                } else {
                    chartData[date] = 1;
                }
                return chartData;
            }, {});
            this.options.series = [
                {
                    name: "Number of orders",
                    data: [...Object.values(chartData)]
                }
            ]
            this.options.xaxis = {
                categories: Object.keys(chartData).map(k => {
                    parts = k.split(" ");
                    return `${parts[1]} ${parts[2]} ${parts[3]}`;
                })
            }
            break;
        case "USER":
            chartData = this.dataList.reduce((chartData, data) => {
                const date = (new Date(data.created_at)).toDateString();
                if (chartData[date]) {
                    chartData[date]++;
                } else {
                    chartData[date] = 1;
                }
                return chartData;
            }, {});
            this.options.series = [
                {
                    name: "Number of users",
                    data: [...Object.values(chartData)]
                }
            ]
            this.options.xaxis = {
                categories: Object.keys(chartData).map(k => {
                    parts = k.split(" ");
                    return `${parts[1]} ${parts[2]} ${parts[3]}`;
                })
            }
            break;
        default:
    }


    this.chart = new ApexCharts(document.querySelector(this.selector), this.options);
    this.render();
}

Chart.prototype.render = function () {
    this.chart.render();
}






function ViewList(id, selector) {
    this.id = id;
    this.selector = selector;
    this.dataList = [];
    return this;
}

ViewList.prototype.setData = function (dataList) {
    this.dataList = (dataList.length > 5 ? dataList.slice(0, 5) : dataList);
    this.selector = document.querySelector(this.selector);

    this.updateView();
}

ViewList.prototype.updateView = function () {
    let content = "";
    switch (this.id) {
        case "ORDER":
            content = this.dataList.reduce((content, data) => {
                return content +
                    `<div class="notify-card">
                        <div class="notify-card-icon"><i class="bi bi-file-text icon-32"></i></div>
                        <div class="notify-card-info">
                            <div class="notify-card-bold-text">Total: $${Math.round(data.total_price * 100) / 100}</div>
                            <div class="notify-card-text">Created at: ${data.created_at}</div>
                        </div>
                    </div>`
            }, "");
            break;
        case "USER":
            content = this.dataList.reduce((content, data) => {
                return content +
                    `<div class="notify-card">
                        <div class="notify-card-icon"><i class="bi bi-person icon-32"></i></div>
                        <div class="notify-card-info">
                            <div class="notify-card-bold-text">Username: ${data.username}</div>
                            <div class="notify-card-text">Created at: ${data.created_at}</div>
                        </div>
                    </div>`;
            }, "");
            break;
        default:
    }

    this.selector.innerHTML = content;
}