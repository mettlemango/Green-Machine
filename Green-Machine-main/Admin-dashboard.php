<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>GREEN | Admin Dashboard</title>
        <link rel="stylesheet" href="assets/css/styles.css">
    </head>
    <body>
        <header class="fixed-header">
            <div class="headerContainer">
                <img src="assets/images/logo.png" class="headerPic" alt="GREEN Logo">
            </div>
            <nav>
            <a href="logout.php">Logout</a>
            </nav>
        </header>
    
        <div class="main-container">
            <div class="admin-dashboard">
                <h1>Admin Dashboard</h1>
                
                <div class="dashboard-grid">
                    <div class="dashboard-card">
                        <a href="report.php" class="dashboard-link">
                            <img src="assets/images/report.png" alt="Reports" class="dashboard-icon">
                            <span class="dashboard-label">Reports</span>
                        </a>
                    </div>
                    
                    <div class="dashboard-card">
                        <a href="inventory.html" class="dashboard-link">
                            <img src="assets/images/inventory.png" alt="Inventory" class="dashboard-icon">
                            <span class="dashboard-label">Inventory</span>
                        </a>
                    </div>
                    
                    <div class="dashboard-card">
                        <a href="admin_orders.php" class="dashboard-link">
                            <img src="assets/images/orders.png" alt="Orders" class="dashboard-icon">
                            <span class="dashboard-label">Orders</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    
        <script src="script.js"></script>
    </body>
    </html>