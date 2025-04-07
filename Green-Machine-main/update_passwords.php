<?php
$conn = new mysqli("localhost", "root", "", "greenmachine");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Hash and update passwords
$users = [
    ['username' => 'admin', 'new_password' => 'admin123'],
    ['username' => 'Kyle', 'new_password' => 'test1234']
];

foreach ($users as $user) {
    $hashed_password = password_hash($user['new_password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE login_test SET password = ? WHERE username = ?");
    $stmt->bind_param("ss", $hashed_password, $user['username']);
    
    if ($stmt->execute()) {
        echo "Updated password for {$user['username']}\n";
    } else {
        echo "Error updating {$user['username']}: " . $conn->error . "\n";
    }
}

$conn->close();
?>