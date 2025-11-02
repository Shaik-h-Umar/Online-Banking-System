<?php
session_start();
require_once "db_config.php";

$response = [
    "balance" => 0,
    "sent" => 0,
    "received" => 0,
    "total_txn" => 0,
    "recent" => []
];

$user_id = $_SESSION['user_id'];

// Fetch account
$acc = $conn->query("SELECT balance FROM accounts WHERE user_id=$user_id")->fetch_assoc();
$response["balance"] = $acc["balance"];

// This month sent
$sent = $conn->query("
SELECT SUM(amount) AS total 
FROM transactions 
WHERE sender_id=$user_id AND MONTH(created_at)=MONTH(CURRENT_DATE())
")->fetch_assoc();
$response["sent"] = $sent["total"] ?? 0;

// This month received
$rec = $conn->query("
SELECT SUM(amount) AS total 
FROM transactions 
WHERE receiver_id=$user_id AND MONTH(created_at)=MONTH(CURRENT_DATE())
")->fetch_assoc();
$response["received"] = $rec["total"] ?? 0;

// Total Txns
$txn = $conn->query("
SELECT COUNT(*) AS total 
FROM transactions 
WHERE sender_id=$user_id OR receiver_id=$user_id
")->fetch_assoc();
$response["total_txn"] = $txn["total"];

// Recent 5
$res = $conn->query("
SELECT * FROM transactions 
WHERE sender_id=$user_id OR receiver_id=$user_id
ORDER BY id DESC LIMIT 5
");

while($row = $res->fetch_assoc()){
    $response["recent"][] = $row;
}

echo json_encode($response);
?>
