<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GREEN | Admin Orders</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .main-content {
            padding-top: 100px;
            max-width: 1400px;
            margin: 0 auto;
            padding-bottom: 50px;
        }
        
        .dashboard-title {
            text-align: center;
            color: #024033;
            margin-bottom: 30px;
            font-size: 2em;
        }
        
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 20px;
            padding: 0 20px;
        }
        
        .table-container {
            background-color: #fbeee0;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .table-container h2 {
            color: #024033;
            border-bottom: 2px solid #024033;
            padding-bottom: 10px;
            margin-top: 0;
        }
        
        .order-item {
            background-color: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .order-item p {
            margin: 5px 0;
            color: #39241B;
        }
        
        .remove-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        .remove-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <header class="fixed-header">
        <div class="headerContainer">
            <img src="assets/images/logo.png" class="headerPic" alt="GREEN Logo">
        </div>
    </header>

    <div class="sidebar" onmouseover="expandSidebar()" onmouseout="collapseSidebar()">
        <a href="admin_orders.php"><button class="button2">Orders</button></a>
        <a href="inventory.html"><button class="button2">Inventory</button></a>
        <a href="report.php"><button class="button2">Report</button></a>        
    </div>

    <div class="main-content">
        <h1 class="dashboard-title">Current Orders</h1>
        
        <div class="tables-grid" id="tables-container">
            <!-- Tables will be dynamically inserted here -->
        </div>
    </div>

    <script>
        // Function to fetch orders from the server
        function fetchOrders() {
            fetch('fetch_orders.php')
            .then(response => response.json())
            .then(data => {
                console.log(data);
                displayOrders(data);
            })
            .catch(error => console.error('Error fetching orders:', error));
        }

        // Function to display orders on the page
        function displayOrders(orders) {
            const tablesContainer = document.getElementById('tables-container');
            tablesContainer.innerHTML = '';
            
            // Create a map to organize orders by table
            const tablesMap = new Map();
            
            // Group orders by table number
            orders.forEach(order => {
                if (!tablesMap.has(order.table_number)) {
                    tablesMap.set(order.table_number, []);
                }
                tablesMap.get(order.table_number).push(order);
            });
            
            // Create table containers for each table with orders
            tablesMap.forEach((tableOrders, tableNumber) => {
                const tableDiv = document.createElement('div');
                tableDiv.className = 'table-container';
                tableDiv.id = `table-${tableNumber}-orders`;
                
                let tableHTML = `<h2>Table ${tableNumber}</h2><div id="table-${tableNumber}-container">`;
                
                tableOrders.forEach(order => {
                    tableHTML += `
                        <div class="order-item" data-order-id="${order.id}">
                            <p><strong>Order ID:</strong> ${order.id}</p>
                            <p><strong>Item:</strong> ${order.item_name}</p>
                            <p><strong>Quantity:</strong> ${order.quantity}</p>
                            <p><strong>Price:</strong> ₽${order.price}</p>
                            <button class="remove-btn" onclick="removeOrder(${order.id}, ${order.table_number})">Remove Order</button>
                        </div>
                    `;
                });
                
                tableHTML += `</div>`;
                tableDiv.innerHTML = tableHTML;
                tablesContainer.appendChild(tableDiv);
            });
        }

        // Function to remove order from UI and database
        function removeOrder(orderId, tableNumber) {
            if (confirm('Are you sure you want to remove this order?')) {
                fetch('remove_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ orderId: orderId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const orderDiv = document.querySelector(`.order-item[data-order-id="${orderId}"]`);
                        if (orderDiv) {
                            orderDiv.remove();
                        }
                        // Refresh the table if empty
                        const tableContainer = document.getElementById(`table-${tableNumber}-container`);
                        if (tableContainer && tableContainer.children.length === 0) {
                            document.getElementById(`table-${tableNumber}-orders`).remove();
                        }
                    } else {
                        alert('Error removing order: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // Fetch orders when the page loads and set interval for updates
        document.addEventListener('DOMContentLoaded', function() {
            fetchOrders();
            setInterval(fetchOrders, 10000); // Refresh every 10 seconds
        });
    </script>
</body>
</html>