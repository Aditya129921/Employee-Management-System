<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

require_once 'db.php';

// Fetch all employees ordered by ID
$result = $conn->query("SELECT * FROM employees ORDER BY employee_id ASC");
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employees - Employee Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="dashboard.php" class="brand">EMS</a>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="add.php">Add Employee</a>
            <a href="view.php" class="active">View Employees</a>
            <a href="department.php">Departments</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </nav>

    <div class="container" style="max-width: 1100px;">
        <h2 class="page-title">Employee List</h2>
        <p class="page-subtitle">All registered employees are listed below</p>

        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert success">Employee deleted successfully.</div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Emp ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Department</th>
                        <th>Salary</th>
                        <th>Joining Date</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['employee_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo htmlspecialchars($row['department']); ?></td>
                                <td><?php echo htmlspecialchars($row['salary']); ?></td>
                                <td><?php echo htmlspecialchars($row['joining_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                <td>
                                    <a href="edit_employee.php?id=<?php echo urlencode($row['employee_id']); ?>">Edit</a>
                                    <a href="delete_employee.php?id=<?php echo urlencode($row['employee_id']); ?>" class="delete">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <?php $result->free(); ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" style="text-align: center; color: #718096;">No employees found. <a href="add.php">Add one now</a>.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="page-actions">
            <a href="add.php" class="btn">+ Add Employee</a>
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>