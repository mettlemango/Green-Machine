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

    // Calculate total and filter selected items
    $checkedOutItems = [];
    foreach ($selectedItems as $index) {
        if (isset($cart[$index])) {
            $item = $cart[$index];
            $totalAmount += $item['price'] * $item['qty'];
            $checkedOutItems[] = $item;

            // Update inventory in the database
            $stmt = $conn->prepare("UPDATE stock_test SET stock = stock - ? WHERE item = ?");
            $stmt->bind_param("is", $item['qty'], $item['name']);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Insert order into the database
    $stmt = $conn->prepare("INSERT INTO orders (item_name, quantity, total_price) VALUES (?, ?, ?)");
    foreach ($checkedOutItems as $item) {
        $totalPrice = $item['price'] * $item['qty'];
        $stmt->bind_param("sid", $item['name'], $item['qty'], $totalPrice);
        $stmt->execute();
    }
    $stmt->close();

    // Update session cart
    $_SESSION['cart'] = array_values(array_filter($cart, function ($key) use ($selectedItems) {
        return !in_array($key, $selectedItems);
    }, ARRAY_FILTER_USE_KEY));

    // Display checkout summary
    echo "<div class='main-content'>";
    echo "<h1 class='header-title'>Checkout Summary</h1>";
    echo "<table class='checkout-table'>";
    echo "<tr><th>Item</th><th>Price</th><th>Quantity</th><th>Total</th></tr>";
    foreach ($checkedOutItems as $item) {
        echo "<tr>";
        echo "<td>{$item['name']}</td>";
        echo "<td>₱" . number_format($item['price'], 2) . "</td>";
        echo "<td>{$item['qty']}</td>";
        echo "<td>₱" . number_format($item['price'] * $item['qty'], 2) . "</td>";
        echo "</tr>";
    }
    echo "<tr><td colspan='3'><strong>Total Amount</strong></td><td><strong>₱" . number_format($totalAmount, 2) . "</strong></td></tr>";
    echo "</table>";

    echo "<p class='success-message'>Thank you for your purchase!</p>";
    echo "<a href='index.php' class='back-button'>Return to Home</a>";
    echo "</div>";
} else {
    echo "<div class='error-message'>Invalid request method.</div>";
}

$conn->close();
?>