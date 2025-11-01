<?php
session_start();
require_once "db_config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sender_id = $_SESSION['user_id'];
    $receiver_account_number = $_POST['receiver'];
    $amount = $_POST['amount'];
    $description = $_POST['desc'];
    $password = $_POST['password'];

    // Validate form data
    if (empty($receiver_account_number) || empty($amount) || empty($password)) {
        header("Location: ../pages/transfer.php?error=Please fill in all fields");
        exit();
    }

    if ($amount <= 0) {
        header("Location: ../pages/transfer.php?error=Invalid amount");
        exit();
    }

    // Get sender's data
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sender_id);
    $stmt->execute();
    $sender_result = $stmt->get_result();
    $sender = $sender_result->fetch_assoc();

    // Verify password
    if (!password_verify($password, $sender['password'])) {
        header("Location: ../pages/transfer.php?error=Incorrect password");
        exit();
    }

    // Check for sufficient balance
    if ($sender['balance'] < $amount) {
        header("Location: ../pages/transfer.php?error=Insufficient balance");
        exit();
    }

    // Get receiver's data
    $sql = "SELECT * FROM users WHERE account_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $receiver_account_number);
    $stmt->execute();
    $receiver_result = $stmt->get_result();

    if ($receiver_result->num_rows == 0) {
        header("Location: ../pages/transfer.php?error=Receiver not found");
        exit();
    }

    $receiver = $receiver_result->fetch_assoc();
    $receiver_id = $receiver['id'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Deduct from sender
        $sql = "UPDATE users SET balance = balance - ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $amount, $sender_id);
        $stmt->execute();

        // Add to receiver
        $sql = "UPDATE users SET balance = balance + ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $amount, $receiver_id);
        $stmt->execute();

        // Record transaction
        $sql = "INSERT INTO transactions (sender_id, receiver_id, amount, description) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iids", $sender_id, $receiver_id, $amount, $description);
        $stmt->execute();

        // Commit transaction
        $conn->commit();

        header("Location: ../pages/transfer.php?success=Transfer successful");
        exit();

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        header("Location: ../pages/transfer.php?error=Transfer failed");
        exit();
    }
}
?>