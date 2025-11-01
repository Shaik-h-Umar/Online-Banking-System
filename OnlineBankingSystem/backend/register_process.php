<?php
session_start();
require_once "db_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: ../pages/register.php");
        exit();
    }

    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        $_SESSION['error'] = "Email already registered!";
        header("Location: ../pages/register.php");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (fullname, email, mobile, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $email, $mobile, $hashedPassword);

    if ($stmt->execute()) {
        $new_user_id = $conn->insert_id;
        $_SESSION['user_id'] = $new_user_id;
        $_SESSION['fullname'] = $full_name;
        $_SESSION['email'] = $email;
        header("Location: ../pages/dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong!";
        header("Location: ../pages/register.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>