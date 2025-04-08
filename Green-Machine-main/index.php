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
        <img src="assets/images/cart.png" class="cartPic">
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
    <script src="script.js"></script>
    <div class="container">
        
    </div>
</body>
</html>