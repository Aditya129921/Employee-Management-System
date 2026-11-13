<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

require_once 'db.php';

// Fetch department names from DB — used for both validation and the dropdown
$dept_list = [];
$dr = $conn->query("SELECT name FROM departments ORDER BY name ASC");
if ($dr) { while ($d = $dr->fetch_assoc()) { $dept_list[] = $d['name']; } $dr->free(); }

$allowed_genders = ['Male', 'Female', 'Other'];
$message     = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employee_id  = trim($_POST["employee_id"]  ?? '');
    $name         = trim($_POST["name"]         ?? '');
    $email        = trim($_POST["email"]        ?? '');
    $gender       = $_POST["gender"]            ?? '';
    $phone        = trim($_POST["phone"]        ?? '');
    $department   = $_POST["department"]        ?? '';
    $salary       = $_POST["salary"]            ?? '';
    $joining_date = $_POST["joining_date"]      ?? '';
    $address      = trim($_POST["address"]      ?? '');

    // Basic server-side validation uses $allowed_genders and $dept_list (fetched above)

    if (empty($employee_id) || empty($name) || empty($email)) {
        $message     = "Employee ID, Name, and Email are required.";
        $messageType = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message     = "Please enter a valid email address.";
        $messageType = "error";
    } elseif (!in_array($gender, $allowed_genders)) {
        $message     = "Invalid gender selected.";
        $messageType = "error";
    } elseif (!in_array($department, $dept_list)) {
        $message     = "Invalid department selected.";
        $messageType = "error";
    } elseif (!is_numeric($salary) || (float)$salary < 0) {
        $message     = "Salary must be a positive number.";
        $messageType = "error";
    } else {
        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare(
            "INSERT INTO employees (employee_id, name, email, gender, phone, department, salary, joining_date, address)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssssssdss", $employee_id, $name, $email, $gender, $phone, $department, $salary, $joining_date, $address);

        if ($stmt->execute()) {
            $message     = "Employee added successfully!";
            $messageType = "success";
        } else {
            // Show a user-friendly error; avoid leaking internal DB info
            if ($conn->errno === 1062) {
                $message = "Error: Employee ID already exists.";
            } else {
                $message = "Error saving employee. Please try again.";
            }
            $messageType = "error";
        }
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
    <title>Add Employee - Employee Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="dashboard.php" class="brand">EMS</a>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="add.php" class="active">Add Employee</a>
            <a href="view.php">View Employees</a>
            <a href="department.php">Departments</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h2 class="page-title">Add New Employee</h2>
        <p class="page-subtitle">Fill in the details below to add a new employee</p>

        <?php if ($message !== ""): ?>
            <div class="alert <?php echo htmlspecialchars($messageType); ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="add.php">
                <label for="employee_id">Employee ID:</label>
                <input type="text" id="employee_id" name="employee_id" placeholder="e.g. EMP001" required>

                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Full name" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="email@example.com" required>

                <label>Gender:</label>
                <div class="radio-group">
                    <label style="margin: 0; font-weight: normal;"><input type="radio" name="gender" value="Male" checked> Male</label>
                    <label style="margin: 0; font-weight: normal;"><input type="radio" name="gender" value="Female"> Female</label>
                    <label style="margin: 0; font-weight: normal;"><input type="radio" name="gender" value="Other"> Other</label>
                </div>

                <label for="phone">Phone:</label>
                <input type="tel" id="phone" name="phone" placeholder="Phone number" required>

                <label for="department">Department:</label>
                <select id="department" name="department" required>
                    <option value="">Select Department</option>
                    <?php foreach ($dept_list as $dname):
                        $sel = (isset($_POST['department']) && $_POST['department'] === $dname) ? 'selected' : ''; ?>
                        <option value="<?php echo htmlspecialchars($dname); ?>" <?php echo $sel; ?>>
                            <?php echo htmlspecialchars($dname); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="salary">Salary:</label>
                <input type="number" id="salary" name="salary" placeholder="e.g. 50000" min="0" step="0.01" required>

                <label for="joining_date">Joining Date:</label>
                <input type="date" id="joining_date" name="joining_date" required>

                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="3" placeholder="Full address" required></textarea>

                <div class="form-actions">
                    <input type="submit" value="Add Employee">
                    <input type="reset" value="Clear Form">
                </div>
            </form>
        </div>

        <div class="page-actions">
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
