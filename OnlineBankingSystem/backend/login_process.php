<?php
session_start();
require_once "db_config.php";

$email = $_POST['email'];
$password = $_POST['password'];

// Prepare and execute query
$sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
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
?>
