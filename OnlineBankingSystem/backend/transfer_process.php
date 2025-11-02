<?php
session_start();
require_once "db_config.php";

// Helper function for clean redirects with messages
function redirect_with_message($type, $message, $location) {
    $_SESSION['flash_message'] = ['type' => $type, 'message' => $message];
    header("Location: $location");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/transfer.php");
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_account_number = $_POST['receiver'] ?? '';
$amount = (float)($_POST['amount'] ?? 0);
$password = $_POST['password'] ?? '';

if (empty($receiver_account_number) || $amount <= 0 || empty($password)) {
    redirect_with_message('error', 'Please fill all required fields.', '../pages/transfer.php');
}

$conn->begin_transaction();

try {
    // Get sender's details and lock the row for update
    $stmt = $conn->prepare("SELECT id, password, balance FROM users WHERE id = ? FOR UPDATE");
    $stmt->bind_param("i", $sender_id);
    $stmt->execute();
    $sender = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$sender) { throw new Exception("Sender account not found."); }
    if (!password_verify($password, $sender['password'])) { throw new Exception("Incorrect password."); }
    if ($sender['balance'] < $amount) { throw new Exception("Insufficient balance."); }

    // Get recipient's details and lock for update
    $stmt = $conn->prepare("SELECT id, balance FROM users WHERE account_number = ? FOR UPDATE");
    $stmt->bind_param("s", $receiver_account_number);
    $stmt->execute();
    $receiver = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$receiver) { throw new Exception("Recipient account not found."); }
    if ($sender['id'] === $receiver['id']) { throw new Exception("You cannot transfer money to your own account."); }

    // --- Execution ---
    // Deduct from sender
    $stmt = $conn->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
    $stmt->bind_param("di", $amount, $sender['id']);
    $stmt->execute();
    if ($stmt->affected_rows !== 1) { throw new Exception("Failed to update sender balance."); }
    $stmt->close();

    // Add to receiver
    $stmt = $conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt->bind_param("di", $amount, $receiver['id']);
    $stmt->execute();
    if ($stmt->affected_rows !== 1) { throw new Exception("Failed to update recipient balance."); }
    $stmt->close();

    // Record transactions (Note: This version does not save the description)
    $stmt = $conn->prepare("INSERT INTO transactions (sender_id, receiver_id, amount, type) VALUES (?, ?, ?, 'sent')");
    $stmt->bind_param("iid", $sender['id'], $receiver['id'], $amount);
    $stmt->execute();
    if ($stmt->affected_rows !== 1) { throw new Exception("Failed to log sender transaction."); }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO transactions (sender_id, receiver_id, amount, type) VALUES (?, ?, ?, 'received')");
    $stmt->bind_param("iid", $sender['id'], $receiver['id'], $amount);
    $stmt->execute();
    if ($stmt->affected_rows !== 1) { throw new Exception("Failed to log recipient transaction."); }
    $stmt->close();

    // Commit the transaction
    $conn->commit();
    redirect_with_message('success', 'Transfer successful!', '../pages/transfer.php');

} catch (Exception $e) {
    $conn->rollback();
    redirect_with_message('error', $e->getMessage(), '../pages/transfer.php');
}
?>