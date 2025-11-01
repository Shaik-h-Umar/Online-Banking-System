<?php
session_start();
ob_start();
require_once "db_config.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../pages/login.php?error=Invalid request");
    exit();
}

$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Email format validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../pages/login.php?error=Invalid email format");
    exit();
}

// Prepare and execute query
$sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {

        // Session store
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["fullname"] = $user["fullname"];
        $_SESSION["email"] = $user["email"];

        header("Location: ../pages/dashboard.php");
        exit();
    } else {
        header("Location: ../pages/login.php?error=Invalid password");
        exit();
    }
} else {
    header("Location: ../pages/login.php?error=User not found");
    exit();
}
