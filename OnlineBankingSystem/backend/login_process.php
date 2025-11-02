<?php
session_start();
require_once "db_config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/login.php?error=Invalid request");
    exit();
}

$email = trim($_POST['email']);
$password = trim($_POST['password']);

if (empty($email) || empty($password)) {
    header("Location: ../pages/login.php?error=Email and password are required");
    exit();
}

// Prepare and execute query to fetch user details
$sql = "SELECT id, fullname, email, password, account_number FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {

        // Regenerate session ID for security
        session_regenerate_id(true);

        // Store all necessary user data in the session
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["fullname"] = $user["fullname"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["account_number"] = $user["account_number"]; // CRITICAL FIX

        header("Location: ../pages/dashboard.php");
        exit();
    } else {
        header("Location: ../pages/login.php?error=Invalid email or password");
        exit();
    }
} else {
    header("Location: ../pages/login.php?error=Invalid email or password");
    exit();
}

$stmt->close();
$conn->close();
?>