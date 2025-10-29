<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        die("❌ Passwords do not match!");
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $checkEmail = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        die("❌ Email already registered!");
    }

    $stmt = $conn->prepare("INSERT INTO users (full_name, email, mobile, password) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss", $full_name, $email, $mobile, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Registration Successful! Please Login'); window.location.href='../Frontend/login.php';</script>";
    } else {
        echo "❌ Something went wrong!";
    }

    $stmt->close();
    $conn->close();
}
?>
