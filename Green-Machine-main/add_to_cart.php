<?php
session_start();
$name = $_POST['name'];
$price = floatval($_POST['price']);
$qty = intval($_POST['qty']);

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$_SESSION['cart'][] = ['name' => $name, 'price' => $price, 'qty' => $qty];
echo json_encode(['success' => true]);
?>
