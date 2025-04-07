<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "greenmachine");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    echo json_encode(['success' => false, 'message' => 'Missing data']);
    exit;
}

$username = $_POST['username'];

// Use prepared statement
$stmt = $conn->prepare("SELECT id, username, password, role FROM login_test WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    // Verify password (assuming you've hashed passwords in DB)
    if (password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['logged_in'] = true;
        
        // Regenerate session ID to prevent fixation
        session_regenerate_id(true);
        
        echo json_encode([
            'success' => true,
            'redirect' => ($user['role'] === 'admin') ? 'admin-dashboard.php' : 'index.php'
        ]);
        exit;
    }
}

// Generic error message to prevent username enumeration
echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
exit;
?>