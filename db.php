<?php
/**
 * db.php — Shared database connection helper.
 * Include this file at the top of every page that needs DB access.
 */

$conn = new mysqli("localhost", "root", "", "employee_management");

if ($conn->connect_error) {
    // In production, log this error and show a friendly page instead
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

// Use UTF-8 for all queries
$conn->set_charset("utf8mb4");
