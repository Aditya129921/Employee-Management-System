<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

require_once 'db.php';

$total_employees  = 0;
$total_departments = 0;

$result = $conn->query("SELECT COUNT(*) AS count FROM employees");
if ($result) {
    $total_employees = (int)$result->fetch_assoc()['count'];
    $result->free();
}

$result = $conn->query("SELECT COUNT(*) AS count FROM departments");
if ($result) {
    $total_departments = (int)$result->fetch_assoc()['count'];
    $result->free();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Employee Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="dashboard.php" class="brand">EMS</a>
        <div class="nav-links">
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="add.php">Add Employee</a>
            <a href="view.php">View Employees</a>
            <a href="department.php">Departments</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="page-title">Dashboard</h2>
        <p class="page-subtitle">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>! Here's a quick overview.</p>

        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Employees</h3>
                <p class="number"><?php echo $total_employees; ?></p>
            </div>
            <div class="stat-card">
                <h3>Active Departments</h3>
                <p class="number"><?php echo $total_departments; ?></p>
            </div>
        </div>

        <h3 style="margin-bottom: 12px; color: #4a5568;">Quick Actions</h3>
        <div class="quick-links">
            <a href="add.php">+ Add Employee</a>
            <a href="view.php">View All Employees</a>
            <a href="department.php">Departments</a>
        </div>
    </div>
</body>
</html>
