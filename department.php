<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

require_once 'db.php';

// Fetch departments with live employee count via LEFT JOIN
$departments = [];
$sql = "SELECT d.id, d.name, d.description, d.manager_name, d.manager_email,
               d.location, d.annual_budget, d.established_year,
               COUNT(e.employee_id) AS emp_count
        FROM departments d
        LEFT JOIN employees e ON e.department = d.name
        GROUP BY d.id
        ORDER BY d.name ASC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
    $result->free();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments - Employee Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .dept-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }
        .dept-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 28px;
            border-left: 5px solid #4f86f7;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .dept-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        }
        .dept-card h3 {
            font-size: 1.3rem;
            color: #2d3748;
            margin: 0 0 6px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .dept-badge {
            background: #ebf4ff;
            color: #4f86f7;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }
        .dept-desc {
            font-size: 0.88rem;
            color: #718096;
            margin: 10px 0 18px;
            line-height: 1.6;
        }
        .dept-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .dept-meta-item {
            background: #f7fafc;
            border-radius: 8px;
            padding: 10px 12px;
        }
        .dept-meta-item .label {
            font-size: 0.72rem;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .dept-meta-item .value {
            font-size: 0.9rem;
            color: #2d3748;
            font-weight: 600;
            word-break: break-word;
        }
        .dept-meta-item.full-width {
            grid-column: 1 / -1;
        }
        .emp-count-pill {
            display: inline-block;
            background: linear-gradient(135deg, #4f86f7, #7c3aed);
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 20px;
            margin-top: 16px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="dashboard.php" class="brand">EMS</a>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="add.php">Add Employee</a>
            <a href="view.php">View Employees</a>
            <a href="department.php" class="active">Departments</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </nav>

    <div class="container" style="max-width: 1200px;">
        <h2 class="page-title">Departments</h2>
        <p class="page-subtitle">Overview of all departments — managers, budgets, locations and headcount</p>

        <?php if (count($departments) > 0): ?>
            <div class="dept-grid">
                <?php foreach ($departments as $dept): ?>
                    <div class="dept-card">
                        <h3>
                            <?php echo htmlspecialchars($dept['name']); ?>
                            <span class="dept-badge">Est. <?php echo htmlspecialchars($dept['established_year']); ?></span>
                        </h3>
                        <p class="dept-desc"><?php echo htmlspecialchars($dept['description']); ?></p>

                        <div class="dept-meta">
                            <div class="dept-meta-item">
                                <div class="label">Manager</div>
                                <div class="value"><?php echo htmlspecialchars($dept['manager_name'] ?? '—'); ?></div>
                            </div>
                            <div class="dept-meta-item">
                                <div class="label">Annual Budget</div>
                                <div class="value">₹<?php echo number_format((float)$dept['annual_budget'], 0); ?></div>
                            </div>
                            <div class="dept-meta-item full-width">
                                <div class="label">Manager Email</div>
                                <div class="value"><?php echo htmlspecialchars($dept['manager_email'] ?? '—'); ?></div>
                            </div>
                            <div class="dept-meta-item full-width">
                                <div class="label">Location</div>
                                <div class="value"><?php echo htmlspecialchars($dept['location'] ?? '—'); ?></div>
                            </div>
                        </div>

                        <div class="emp-count-pill">
                            👥 <?php echo (int)$dept['emp_count']; ?> Employee<?php echo $dept['emp_count'] != 1 ? 's' : ''; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No departments found. <a href="add.php">Add employees</a> to see departments here.</p>
        <?php endif; ?>

        <div class="page-actions" style="margin-top: 32px;">
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            <a href="view.php" class="btn">View All Employees</a>
        </div>
    </div>
</body>
</html>

