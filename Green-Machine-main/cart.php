<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head><title>Cart</title></head>
<body>
<h1>Your Cart</h1>
<form action="checkout.php" method="POST">
    <table border="1">
        <tr><th>Select</th><th>Item</th><th>Price</th><th>Qty</th><th>Total</th><th>Action</th></tr>
        <?php foreach ($_SESSION['cart'] ?? [] as $i => $item): ?>
            <tr>
                <td><input type="checkbox" name="checkout_items[]" value="<?= $i ?>"></td>
                <td><?= $item['name'] ?></td>
                <td>₱<?= number_format($item['price'], 2) ?></td>
                <td><?= $item['qty'] ?></td>
                <td>₱<?= number_format($item['price'] * $item['qty'], 2) ?></td>
                <td><a href="remove_from_cart.php?index=<?= $i ?>">Delete</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <button type="submit">Checkout Selected</button>
</form>
</body>
</html>
