<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "greenmachine");

// Get POST data
$username = $conn->real_escape_string($_POST['username']);
$password = $conn->real_escape_string($_POST['password']);

// Query (use password_hash() in production!)
$result = $conn->query("SELECT * FROM login_test WHERE username = '$username' AND password = '$password'");

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role']; // 'admin' or 'user'

    echo json_encode([
        'success' => true,
        'redirect' => ($user['role'] === 'admin') ? 'Admin-dashboard.html' : 'userDashboard.html'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
}
?>


