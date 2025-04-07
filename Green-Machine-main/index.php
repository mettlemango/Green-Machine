<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drinks</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <header class="fixed-header">
        <div class ="headerContainer">
            <img src="images/logo.png" class="headerPic">
        </div>
        <form action="search.php" method="get" class="search-form">
            <input type="text" name="query" placeholder="Search items..." required>
            <button type="submit"><img src="images/search.png" class="searchPic"></button>
        </form>
        <img src="assets/images/cart.png" class="cartPic">
        <nav>
            <a href="#">Sign up</a>
            <a>|</a>
            <a href="#">Login</a>
        </nav>
    </header>
    <script src="script.js"></script>
</body>
</html>