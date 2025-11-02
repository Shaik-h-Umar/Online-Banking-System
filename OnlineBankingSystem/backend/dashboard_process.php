<?php
session_start();
require_once "db_config.php";

// Initialize response structure
$response = [
    "balance" => 0,
    "account_number" => 'N/A', // Add account_number
    "sent" => 0,
    "received" => 0,
    "total_txn" => 0,
    "recent" => []
];

if (!isset($_SESSION['user_id'])) {
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];

// Use prepared statements for all queries

// Fetch user balance and account number
$stmt = $conn->prepare("SELECT balance, account_number FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($user = $result->fetch_assoc()) {
    $response["balance"] = $user["balance"] ?? 0;
    $response["account_number"] = $user["account_number"] ?? 'N/A';
}
$stmt->close();

// This month sent
$stmt = $conn->prepare("SELECT SUM(amount) AS total FROM transactions WHERE sender_id = ? AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($sent = $result->fetch_assoc()) {
    $response["sent"] = $sent["total"] ?? 0;
}
$stmt->close();

// This month received
$stmt = $conn->prepare("SELECT SUM(amount) AS total FROM transactions WHERE receiver_id = ? AND MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($rec = $result->fetch_assoc()) {
    $response["received"] = $rec["total"] ?? 0;
}
$stmt->close();

// Total Txns
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM transactions WHERE sender_id = ? OR receiver_id = ?");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($txn = $result->fetch_assoc()) {
    $response["total_txn"] = $txn["total"] ?? 0;
}
$stmt->close();

// Recent 5 transactions
$stmt = $conn->prepare("SELECT * FROM transactions WHERE sender_id = ? OR receiver_id = ? ORDER BY id DESC LIMIT 5");
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$res = $stmt->get_result();

while ($row = $res->fetch_assoc()) {
    if ($row['sender_id'] == $user_id) {
        $row['type'] = 'sent';
    } else {
        $row['type'] = 'received';
    }
    $response["recent"][] = $row;
}
$stmt->close();

header('Content-Type: application/json');
echo json_encode($response);
?>