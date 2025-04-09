<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "greenmachine";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // If it's a new product being added
    if (!empty($_POST['new_product_name']) && isset($_POST['stock'])) {
        $item = $_POST['new_product_name'];
        $stock = $_POST['stock'];
        $description = isset($_POST['new_product_category']) ? $_POST['new_product_category'] : '';

        $stmt = $conn->prepare("INSERT INTO stock_test (item, stock, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $item, $stock, $description);

        if ($stmt->execute()) {
            header("Location: inventory.html");
            exit();
        } else {
            echo "Error inserting new product: " . $stmt->error;
        }

        $stmt->close();
    }
    // Otherwise, it's updating an existing item's stock
    else if (isset($_POST['stock']) && isset($_POST['item'])) {
        $stock = $_POST['stock'];
        $item = $_POST['item'];

        $stmt = $conn->prepare("UPDATE stock_test SET stock = stock + ? WHERE item = ?");
        $stmt->bind_param("is", $stock, $item);

        if ($stmt->execute()) {
            header("Location: inventory.html");
            exit();
        } else {
            echo "Error updating stock: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Required POST data not set.";
    }

    $conn->close();
}
?>
