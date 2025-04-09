<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Machine</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header class="fixed-header">
        <div class="headerContainer">
            <img src="assets/images/logo.png" class="headerPic">
        </div>
        <form action="index.php" method="get" class="search-form">
            <input type="text" name="query" placeholder="Search items..." value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" required>
            <button type="submit"><img src="assets/images/search.png" class="searchPic"></button>
        </form>

        <!-- Back Button (appears if a search query is active) -->
        <?php if (isset($_GET['query']) && !empty($_GET['query'])): ?>
            <a href="index.php" class="back-button">Back to All Items</a>
        <?php endif; ?>

        <a href="cart.php"><img src="assets/images/cart.png" class="cartPic"></a>
        <nav>
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="#">Sign up</a>
                <a>|</a>
                <a href="login.html">Login</a>
            <?php endif; ?>
        </nav>
    </header>

    <?php
    // Create a connection to the database
    $conn = new mysqli("localhost", "root", "", "greenmachine");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Set up the query based on whether a search query is provided
    $sql = "SELECT DISTINCT item, price, image_path FROM stock_test WHERE price > 0";

    // If a search query is provided, append a condition to filter by the query
    if (isset($_GET['query']) && !empty($_GET['query'])) {
        $search_query = $_GET['query'];
        $sql .= " AND item LIKE '%$search_query%'";
    }

    // Order the items by name
    $sql .= " ORDER BY item";

    // Execute the query
    $items = $conn->query($sql);

    if (!$items) {
        die("Query error: " . $conn->error);
    }
    ?>

    <div class="items-container">
        <?php while ($row = $items->fetch_assoc()): ?>
            <div class="item" data-name="<?= $row['item'] ?>" data-price="<?= $row['price'] ?>">
                <img src="<?= !empty($row['image_path']) ? htmlspecialchars($row['image_path']) : 'assets/images/default-product.png' ?>" 
                     alt="<?= htmlspecialchars($row['item']) ?>"
                     onerror="this.onerror=null;this.src='assets/images/default-product.png'">
                <strong><?= $row['item'] ?></strong><br>
                ₱<?= number_format($row['price'], 2) ?>
            </div>
        <?php endwhile; ?>
    </div>

    <div id="popup-bg"></div>
    <div id="popup">
        <div style="display: flex;">
            <div style="flex:1;"><img id="popup-img" src="" alt="Item Image" width="100%"></div>
            <div style="flex:2; padding-left: 10px;">
                <h2 id="popup-name"></h2>
                <p id="popup-desc">Item description here...</p>
                <p>Price: ₱<span id="popup-price"></span></p>
                <div>
                    <button id="qty-decrease">-</button>
                    <span id="popup-qty">1</span>
                    <button id="qty-increase">+</button>
                </div>
                <button id="add-to-cart">Add to Cart</button>
            </div>
        </div>
    </div>

    <script src="assets/js/script.js"></script>
    <div class="container">
    </div>
</body>
</html>
