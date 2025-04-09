<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "greenmachine";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("<div class='error-message'>Database connection failed: " . $conn->connect_error . "</div>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['checkout_items']) || empty($_POST['checkout_items'])) {
        echo "<div class='error-message'>No items selected for checkout.</div>";
        exit;
    }

    $selectedItems = $_POST['checkout_items'];
    $cart = $_SESSION['cart'] ?? [];
    $totalAmount = 0;
    $checkedOutItems = [];

    foreach ($selectedItems as $index) {
        if (isset($cart[$index])) {
            $item = $cart[$index];
            $totalAmount += $item['price'] * $item['qty'];
            $checkedOutItems[] = $item;

            $stmt = $conn->prepare("UPDATE stock_test SET stock = stock - ? WHERE item = ?");
            $stmt->bind_param("is", $item['qty'], $item['name']);
            $stmt->execute();
            $stmt->close();
        }
    }

    $stmt = $conn->prepare("INSERT INTO orders (item_name, quantity, total_price) VALUES (?, ?, ?)");
    foreach ($checkedOutItems as $item) {
        $totalPrice = $item['price'] * $item['qty'];
        $stmt->bind_param("sid", $item['name'], $item['qty'], $totalPrice);
        $stmt->execute();
    }
    $stmt->close();

    $_SESSION['cart'] = array_values(array_filter($cart, function ($key) use ($selectedItems) {
        return !in_array($key, $selectedItems);
    }, ARRAY_FILTER_USE_KEY));

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout</title>
        <link rel="stylesheet" href="assets/css/styles.css">
        <style>
            .checkout-container { padding-top: 100px; max-width: 1200px; margin: 0 auto; padding-bottom: 50px; }
            .checkout-title { text-align: center; color: #024033; margin-bottom: 30px; font-size: 2em; }
            .checkout-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            .checkout-table th, .checkout-table td { border: 1px solid #ddd; padding: 10px; text-align: center; }
            .checkout-table th { background-color: #024033; color: white; }
            .success-message { text-align: center; color: green; font-size: 1.2em; margin-top: 20px; }
            .back-button { display: block; margin: 20px auto; padding: 10px 20px; background-color: #006937; color: white; text-align: center; text-decoration: none; border-radius: 5px; font-size: 1em; }
            .back-button:hover { background-color: #00512a; }
        </style>
    </head>
    <body>
        <header class="fixed-header">
            <div class="headerContainer">
                <img src="assets/images/logo.png" class="headerPic" alt="GREEN Logo">
            </div>
            <nav>
                <a href="index.php">Home</a>
                <a href="logout.php">Logout</a>
            </nav>
        </header>

        <div class="checkout-container">
            <h1 class="checkout-title">Checkout Summary</h1>
            <table class="checkout-table">
                <tr><th>Item</th><th>Price</th><th>Quantity</th><th>Total</th></tr>
                <?php foreach ($checkedOutItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>₱<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td>₱<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr><td colspan="3"><strong>Total Amount</strong></td><td><strong>₱<?= number_format($totalAmount, 2) ?></strong></td></tr>
            </table>

            <p class="success-message">Thank you for your purchase!</p>
            <a href="index.php" class="back-button">Return to Home</a>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "<div class='error-message'>Invalid request method.</div>";
}

$conn->close();
?>