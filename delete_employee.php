<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

require_once 'db.php';

$message     = "";
$messageType = "";

// Validate and sanitise the ID from the URL immediately
$emp_id = trim($_GET['id'] ?? '');
if (empty($emp_id)) {
    header("Location: view.php");
    exit();
}

// Handle confirmed deletion via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $conn->prepare("DELETE FROM employees WHERE employee_id = ?");
    $stmt->bind_param("s", $emp_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: view.php?deleted=1");
        exit();
    } else {
        $message     = "Error deleting employee. Please try again.";
        $messageType = "error";
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Employee - Employee Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="dashboard.php" class="brand">EMS</a>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="add.php">Add Employee</a>
            <a href="view.php">View Employees</a>
            <a href="department.php">Departments</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="page-title">Delete Employee</h2>
        <p class="page-subtitle">Confirm employee removal</p>

        <?php if ($message !== ""): ?>
            <div class="alert <?php echo htmlspecialchars($messageType); ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="confirm-card">
            <h3>Are you sure?</h3>
            <p>You are about to permanently delete employee <strong><?php echo htmlspecialchars($emp_id); ?></strong>. This action cannot be undone.</p>
            <form method="POST" action="delete_employee.php?id=<?php echo urlencode($emp_id); ?>">
                <div class="form-actions">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    <a href="view.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>