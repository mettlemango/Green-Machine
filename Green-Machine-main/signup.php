<?php
session_start();
header('Content-Type: application/json');

// Database connection
$conn = new mysqli("localhost", "root", "", "greenmachine");

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// Get raw POST data for debugging
$rawData = file_get_contents("php://input");
error_log("Raw POST data: " . $rawData);

// Get form data
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    $data = $_POST; // Fallback to regular POST
}

// Validate required fields
if (empty($data['username']) || empty($data['password']) || empty($data['confirm_password'])) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

$username = trim($data['username']);
$password = $data['password'];
$confirm_password = $data['confirm_password'];

// Username validation
if (strlen($username) < 4 || strlen($username) > 20) {
    echo json_encode(['success' => false, 'message' => 'Username must be 4-20 characters']);
    exit;
}

if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    echo json_encode(['success' => false, 'message' => 'Username can only contain letters, numbers, and underscores']);
    exit;
}

// Password validation
if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
    exit;
}

if (!preg_match('/[A-Z]/', $password)) {
    echo json_encode(['success' => false, 'message' => 'Password must contain at least one uppercase letter']);
    exit;
}

if (!preg_match('/[0-9]/', $password)) {
    echo json_encode(['success' => false, 'message' => 'Password must contain at least one number']);
    exit;
}

if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
    echo json_encode(['success' => false, 'message' => 'Password must contain at least one special character']);
    exit;
}

// Confirm password
if ($password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
    exit;
}

// Check if username exists
$stmt = $conn->prepare("SELECT id FROM login_test WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username already taken']);
    exit;
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$stmt = $conn->prepare("INSERT INTO login_test (username, password, role) VALUES (?, ?, 'user')");
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Account created successfully! Redirecting...']);
} else {
    error_log("Database error: " . $stmt->error);
    echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
}

$stmt->close();
$conn->close();
?>