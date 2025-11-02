<?php
session_start();
require_once "db_config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION['user_id'];
    $amount = floatval($_POST['amount']);

    if ($amount <= 0) {
        header("Location: ../pages/deposit.php?error=Invalid amount entered");
        exit();
    }

    // START TRANSACTION (prevents data corruption)
    $conn->begin_transaction();

    try {
        // Update user balance
        $sql = "UPDATE users SET balance = balance + ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $amount, $user_id);
        $stmt->execute();

        // Record transaction
        $sql2 = "INSERT INTO transactions (sender_id, receiver_id, amount, type)
                 VALUES (NULL, ?, ?, 'received')";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("id", $user_id, $amount);
        $stmt2->execute();

        // Commit
        $conn->commit();

        header("Location: ../pages/deposit.php?success=Amount deposited successfully");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../pages/deposit.php?error=Failed to deposit amount");
        exit();
    }
}
?>
