<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GREEN | Monthly Report</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        body {
            font-family: 'Roboto Condensed', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        
        .main-content {
            padding-top: 100px;
            max-width: 1200px;
            margin: 0 auto;
            padding-bottom: 50px;
        }
        
        .report-container {
            background-color: #fbeee0;
            border-radius: 10px;
            padding: 30px;
            margin: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .report-title {
            color: #024033;
            font-size: 2.5em;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .month-title {
            color: #39241B;
            font-size: 1.8em;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .month-orders {
            border: 2px solid #024033;
            border-radius: 10px;
            padding: 20px;
            background-color: white;
        }
        
        .orders-list {
            list-style-type: none;
            padding: 0;
        }
        
        .orders-list li {
            font-size: 1.1em;
            padding: 10px;
            border-bottom: 1px solid #eee;
            color: #39241B;
        }
        
        .no-orders {
            font-size: 1.2em;
            color: #39241B;
            text-align: center;
            padding: 20px;
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
        <?php
        // Database credentials
        $host = "localhost";
        $username = "root";
        $password = "";
        $dbname = "greenmachine";

        try {
            // Connect to the database
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Get the current month and year
            $currentMonth = date('m');
            $currentYear = date('Y');
            $monthName = date('F');

            // Retrieve orders for the current month from the database
            $stmt = $pdo->prepare("SELECT * FROM orders WHERE MONTH(timestamp) = :month AND YEAR(timestamp) = :year");
            $stmt->bindParam(':month', $currentMonth);
            $stmt->bindParam(':year', $currentYear);
            $stmt->execute();

            // Fetch all rows
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Display the orders
            echo "<div class='report-container'>";
            echo "<h1 class='report-title'>GREEN MACHINE Monthly Report</h1>";
            echo "<h2 class='month-title'>{$monthName} {$currentYear}</h2>";
            echo "<div class='month-orders'>";
            
            if (count($orders) > 0) {
                echo "<ul class='orders-list'>";
                foreach ($orders as $order) {
                    echo "<li>{$order['item_name']} - Quantity: {$order['quantity']} - Price: ₽{$order['price']}</li>";
                }
                echo "</ul>";
            } else {
                echo "<p class='no-orders'>No orders found for {$monthName} {$currentYear}.</p>";
            }
            
            echo "</div>";
            echo "</div>";

        } catch (PDOException $e) {
            echo "<div class='report-container'>";
            echo "<p class='no-orders'>Error loading report: " . $e->getMessage() . "</p>";
            echo "</div>";
        }
        ?>
    </div>

    <script src="assets/js/scripts.js"></script>
</body>
</html>