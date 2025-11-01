<?php
session_start();
require_once "db_config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION['user_id'];
    $amount = $_POST['amount'];

    if ($amount <= 0) {
        header("Location: ../pages/deposit.php?error=Invalid amount");
        exit();
    }

    // Update user's balance in the database
    $sql = "UPDATE users SET balance = balance + ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $amount, $user_id);

    if ($stmt->execute()) {
        header("Location: ../pages/deposit.php?success=Amount deposited successfully");
        exit();
    } else {
        header("Location: ../pages/deposit.php?error=Failed to deposit amount");
        exit();
    }
}
?>