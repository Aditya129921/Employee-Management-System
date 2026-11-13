<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';

    // Demo credentials check (in production, use hashed passwords from DB)
    if ($username === "admin" && $password === "1234") {
        session_regenerate_id(true); // Prevent session fixation
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Employee Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="login-card">
            <h1>Employee Management System</h1>
            <h2>Login to your account</h2>

            <?php if ($error !== ""): ?>
                <div class="alert error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter username" required autofocus autocomplete="username">

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required autocomplete="current-password">

                <input type="submit" value="Login">
            </form>

            <p class="hint">Demo credentials: admin / 1234</p>
        </div>
    </div>
</body>
</html>
