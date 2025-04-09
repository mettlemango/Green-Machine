<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .cart-container {
            padding-top: 100px;
            max-width: 1200px;
            margin: 0 auto;
            padding-bottom: 50px;
        }

        .cart-title {
            text-align: center;
            color: #024033;
            margin-bottom: 30px;
            font-size: 2em;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .cart-table th, .cart-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .cart-table th {
            background-color: #024033;
            color: white;
        }

        .cart-actions {
            text-align: center;
        }

        .checkout-button {
            background-color: #006937;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .checkout-button:hover {
            background-color: #00512a;
        }

        .remove-link {
            color: #e74c3c;
            text-decoration: none;
        }

        .remove-link:hover {
            text-decoration: underline;
        }
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

    <div class="cart-container">
        <h1 class="cart-title">Your Cart</h1>
        <form action="checkout.php" method="POST">
            <table class="cart-table">
                <tr><th>Select</th><th>Item</th><th>Price</th><th>Qty</th><th>Total</th><th>Action</th></tr>
                <?php foreach ($_SESSION['cart'] ?? [] as $i => $item): ?>
                    <tr>
                        <td><input type="checkbox" name="checkout_items[]" value="<?= $i ?>"></td>
                        <td><?= $item['name'] ?></td>
                        <td>₱<?= number_format($item['price'], 2) ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td>₱<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                        <td><a href="remove_from_cart.php?index=<?= $i ?>" class="remove-link">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <div class="cart-actions">
                <button type="submit" class="checkout-button">Checkout Selected</button>
            </div>
        </form>
    </div>
</body>
</html>
