<?php
session_start();
require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Machine Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="fixed-header">
        <div class="headerContainer">
            <img src="images/logo.png" class="headerPic">
        </div>
        <form action="search.php" method="get" class="search-form">
            <input type="text" name="query" placeholder="Search items..." required>
            <button type="submit"><img src="images/search.png" class="searchPic"></button>
        </form>
        <a href="cart.php" class="cart-link">
            <img src="images/cart.png" class="cartPic">
            <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <span class="cart-count"><?= array_sum($_SESSION['cart']) ?></span>
            <?php endif; ?>
        </a>
        <nav>
            <?php if(isset($_SESSION['user'])): ?>
                <span>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                <?php if($_SESSION['user']['role'] === 'admin'): ?>
                    <a href="admin/dashboard.php">Admin Dashboard</a>
                <?php endif; ?>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="signup.php">Sign up</a>
                <span>|</span>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </nav>
    </header>
    
    <div class="container">
        <?php
        // Display featured products
        $stmt = $pdo->query("SELECT * FROM products WHERE stock > 0 LIMIT 8");
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($products as $product):
        ?>
        <div class="product-card">
            <img src="<?= $product['image_url'] ?>" alt="<?= $product['name'] ?>">
            <h3><?= htmlspecialchars($product['name']) ?></h3>
            <p><?= htmlspecialchars($product['description']) ?></p>
            <p class="price">$<?= number_format($product['price'], 2) ?></p>
            <?php if($product['stock'] > 0): ?>
                <button class="add-to-cart" data-id="<?= $product['id'] ?>">Add to Cart</button>
            <?php else: ?>
                <div class="out-of-stock">Out of Stock</div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <script src="script.js"></script>
</body>
</html>