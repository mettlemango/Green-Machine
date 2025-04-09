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
        $price = isset($_POST['product_price']) ? $_POST['product_price'] : 0.00;
        
        $imagePath = '';

if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = basename($_FILES['product_image']['name']);
    $targetFile = $uploadDir . time() . "_" . $filename;

    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile)) {
        $imagePath = $targetFile;
    }
}

$stmt = $conn->prepare("INSERT INTO stock_test (item, stock, description, price, image) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sissd", $item, $stock, $description, $price, $imagePath);


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