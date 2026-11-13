<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

require_once 'db.php';

$message     = "";
$messageType = "";

// Validate the ID from the URL
$emp_id = trim($_GET['id'] ?? '');
if (empty($emp_id)) {
    header("Location: view.php");
    exit();
}

$allowed_genders = ['Male', 'Female', 'Other'];

// Fetch department list from DB for validation and dropdown
$dept_list = [];
$dr = $conn->query("SELECT name FROM departments ORDER BY name ASC");
if ($dr) { while ($d = $dr->fetch_assoc()) { $dept_list[] = $d['name']; } $dr->free(); }
$allowed_departments = $dept_list;

// Default empty employee record
$employee = [
    'employee_id'  => $emp_id,
    'name'         => '',
    'email'        => '',
    'gender'       => 'Male',
    'phone'        => '',
    'department'   => 'IT',
    'salary'       => '',
    'joining_date' => '',
    'address'      => ''
];

// Handle update submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name         = trim($_POST["name"]         ?? '');
    $email        = trim($_POST["email"]        ?? '');
    $gender       = $_POST["gender"]            ?? '';
    $phone        = trim($_POST["phone"]        ?? '');
    $department   = $_POST["department"]        ?? '';
    $salary       = $_POST["salary"]            ?? '';
    $joining_date = $_POST["joining_date"]      ?? '';
    $address      = trim($_POST["address"]      ?? '');

    // Server-side validation
    if (empty($name) || empty($email)) {
        $message     = "Name and Email are required.";
        $messageType = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message     = "Please enter a valid email address.";
        $messageType = "error";
    } elseif (!in_array($gender, $allowed_genders)) {
        $message     = "Invalid gender selected.";
        $messageType = "error";
    } elseif (!in_array($department, $allowed_departments)) {
        $message     = "Invalid department selected.";
        $messageType = "error";
    } elseif (!is_numeric($salary) || (float)$salary < 0) {
        $message     = "Salary must be a positive number.";
        $messageType = "error";
    } else {
        $stmt = $conn->prepare(
            "UPDATE employees
             SET name=?, email=?, gender=?, phone=?, department=?, salary=?, joining_date=?, address=?
             WHERE employee_id=?"
        );
        $stmt->bind_param("sssssdsss", $name, $email, $gender, $phone, $department, $salary, $joining_date, $address, $emp_id);

        if ($stmt->execute()) {
            // Redirect after successful update (Post/Redirect/Get pattern)
            header("Location: edit_employee.php?id=" . urlencode($emp_id) . "&updated=1");
            exit();
        } else {
            $message     = "Error updating employee. Please try again.";
            $messageType = "error";
        }
        $stmt->close();
    }

    // Keep form values on validation error so the user doesn't lose input
    $employee = array_merge($employee, [
        'name'         => $name,
        'email'        => $email,
        'gender'       => $gender,
        'phone'        => $phone,
        'department'   => $department,
        'salary'       => $salary,
        'joining_date' => $joining_date,
        'address'      => $address,
    ]);
}

// Show success message after redirect
if (isset($_GET['updated'])) {
    $message     = "Employee updated successfully!";
    $messageType = "success";
}

// Fetch current employee data (on GET or after validation error)
if ($_SERVER["REQUEST_METHOD"] === "GET" || $messageType === "error") {
    $stmt = $conn->prepare("SELECT * FROM employees WHERE employee_id = ?");
    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $employee = $res->fetch_assoc();
    } else {
        // Employee not found — redirect to the list
        $stmt->close();
        $conn->close();
        header("Location: view.php");
        exit();
    }
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee - Employee Management System</title>
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
        <h2 class="page-title">Edit Employee #<?php echo htmlspecialchars($emp_id); ?></h2>
        <p class="page-subtitle">Update the employee details below</p>

        <?php if ($message !== ""): ?>
            <div class="alert <?php echo htmlspecialchars($messageType); ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="edit_employee.php?id=<?php echo urlencode($emp_id); ?>">
                <label for="emp_id_display">Employee ID:</label>
                <input type="text" id="emp_id_display" value="<?php echo htmlspecialchars($employee['employee_id']); ?>" disabled style="background-color: #f1f5f9;">

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>

                <label>Gender:</label>
                <div class="radio-group">
                    <?php foreach ($allowed_genders as $g): ?>
                        <label style="margin: 0; font-weight: normal;">
                            <input type="radio" name="gender" value="<?php echo $g; ?>"
                                <?php if (strcasecmp($employee['gender'], $g) === 0) echo 'checked'; ?>>
                            <?php echo $g; ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($employee['phone']); ?>" required>

                <label for="department">Department:</label>
                <select id="department" name="department" required>
                    <?php foreach ($dept_list as $d): ?>
                        <option value="<?php echo $d; ?>" <?php if ($employee['department'] === $d) echo 'selected'; ?>>
                            <?php echo $d; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="salary">Salary:</label>
                <input type="number" id="salary" name="salary" value="<?php echo htmlspecialchars($employee['salary']); ?>" min="0" step="0.01" required>

                <label for="joining_date">Joining Date:</label>
                <input type="date" id="joining_date" name="joining_date" value="<?php echo htmlspecialchars($employee['joining_date']); ?>" required>

                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="3" required><?php echo htmlspecialchars($employee['address']); ?></textarea>

                <div class="form-actions">
                    <input type="submit" value="Save Changes">
                    <a href="view.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

        <div class="page-actions">
            <a href="view.php" class="btn btn-secondary">Back to Employee List</a>
        </div>
    </div>
</body>
</html>