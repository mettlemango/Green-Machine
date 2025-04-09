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
}

if (isset($_POST['update_stock'])) {
    // Handle stock update
    $stock = (int)$_POST['stock'];
    $item = $conn->real_escape_string($_POST['item']);

    $stmt = $conn->prepare("UPDATE stock_test SET stock = stock + ? WHERE item = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $bound = $stmt->bind_param("is", $stock, $item);
    if ($bound === false) {
        die("Bind failed: " . $stmt->error);
    }

    $executed = $stmt->execute();
    if ($executed) {
        header("Location: inventory.html?success=1");
        exit();
    } else {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();
    
} elseif (isset($_POST['add_product'])) {
    // Handle new product
    $item = $_POST['new_product_name'];
    $stock = $_POST['initial_stock'];  // Changed from $_POST['stock'] to $_POST['initial_stock']
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
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        // 1. Check file extension
        if ($imageFileType !== 'png') {
            die("Error: Only .png files are allowed. Your file has .$imageFileType extension.");
        }

        // 2. Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['product_image']['tmp_name']);
        finfo_close($finfo);
        
        if ($mime !== 'image/png') {
            die("Error: Invalid MIME type. Expected image/png, got $mime");
        }

        // 3. Verify PNG signature
        $pngSignature = file_get_contents($_FILES['product_image']['tmp_name'], false, null, 0, 8);
        if ($pngSignature === false || substr($pngSignature, 0, 8) !== "\x89PNG\x0d\x0a\x1a\x0a") {
            die("Error: File doesn't have valid PNG signature.");
        }

        // 5. Size check (2MB max)
        if ($_FILES['product_image']['size'] > 2000000) {
            die("Error: File exceeds 2MB limit.");
        }

        // If all checks pass, move the file
        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        } else {
            die("Error: Failed to move uploaded file. Check directory permissions.");
        }
    } else {
        // No image uploaded or upload error
        $uploadError = $_FILES['product_image']['error'] ?? 0;
        if ($uploadError !== UPLOAD_ERR_NO_FILE) {
            die("Upload error: " . $uploadError);
        }
    }

    $stmt = $conn->prepare("INSERT INTO stock_test (item, stock, description, price, image_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sissd", $item, $stock, $description, $price, $imagePath);

    if ($stmt->execute()) {
        header("Location: inventory.html");
        exit();
    } else {
        echo "Error inserting new product: " . $stmt->error;
    }
    $stmt->close();
} else {
    die("Error: Invalid form submission. Received: " . print_r($_POST, true));
}

$conn->close();
?>