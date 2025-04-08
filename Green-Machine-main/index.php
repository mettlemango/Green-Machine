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
        <div class ="headerContainer">
            <img src="assets/images/logo.png" class="headerPic">
        </div>
        <form action="search.php" method="get" class="search-form">
            <input type="text" name="query" placeholder="Search items..." required>
            <button type="submit"><img src="assets/images/search.png" class="searchPic"></button>
        </form>
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
    $conn = new mysqli("localhost", "root", "", "greenmachine");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT DISTINCT item_name, price FROM orders WHERE price > 0 ORDER BY item_name";
    $items = $conn->query($sql);

    if (!$items) {
        die("Query error: " . $conn->error);
    }
    ?>


    <div class="items-container">
        <?php while ($row = $items->fetch_assoc()): ?>
            <div class="item" data-name="<?= $row['item_name'] ?>" data-price="<?= $row['price'] ?>">
                <strong><?= $row['item_name'] ?></strong><br>
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