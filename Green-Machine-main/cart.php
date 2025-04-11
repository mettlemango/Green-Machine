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
            background: none;
            border: none;
            cursor: pointer;
        }

        .remove-link:hover {
            text-decoration: underline;
        }

        .warning-message {
            color: #e74c3c;
            text-align: center;
            margin-bottom: 20px;
            display: none;
        }
    </style>
    <script>
        async function removeFromCart(index) {
            try {
                const response = await fetch('remove_from_cart.php?index=' + index, { method: 'GET' });
                if (response.ok) {
                    document.getElementById('cart-item-' + index).remove();
                    if (document.querySelectorAll('.cart-table tbody tr').length === 0) {
                        document.querySelector('.cart-table tbody').innerHTML = '<tr><td colspan="6">Your cart is empty</td></tr>';
                    }
                } else {
                    alert('Failed to remove item from cart.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while removing the item.');
            }
        }
    </script>
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
        <div id="warning-message" class="warning-message">Please select at least one item to checkout</div>
        <form id="checkout-form" action="checkout.php" method="POST">
            <table class="cart-table">
                <tr><th>Select</th><th>Item</th><th>Price</th><th>Qty</th><th>Total</th><th>Action</th></tr>
                <?php 
                if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): 
                    foreach ($_SESSION['cart'] as $i => $item): ?>
                        <tr id="cart-item-<?= $i ?>">
                            <td><input type="checkbox" name="checkout_items[]" value="<?= $i ?>"></td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>₱<?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['qty'] ?></td>
                            <td>₱<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                            <td><button type="button" class="remove-link" onclick="removeFromCart(<?= $i ?>)">Delete</button></td>
                        </tr>
                    <?php endforeach; 
                else: ?>
                    <tr>
                        <td colspan="6">Your cart is empty</td>
                    </tr>
                <?php endif; ?>
            </table>
            <div class="cart-actions">
                <button type="button" id="checkout-button" class="checkout-button">Checkout Selected</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('checkout-button').addEventListener('click', function(e) {
            const checkboxes = document.querySelectorAll('input[name="checkout_items[]"]:checked');
            const warningMessage = document.getElementById('warning-message');
            
            if (checkboxes.length === 0) {
                // Show warning message
                warningMessage.style.display = 'block';
                
                // Hide the message after 3 seconds
                setTimeout(function() {
                    warningMessage.style.display = 'none';
                }, 3000);
            } else {
                // Proceed with form submission
                document.getElementById('checkout-form').submit();
            }
        });
    </script>
</body>
</html>