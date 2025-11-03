<?php
session_start();
require "db_config.php";

header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];

// The query now fetches transactions specifically for the user's role (sender or receiver)
// and joins with the users table to get the names of the parties involved.
$sql = "
    SELECT
        t.id,
        t.amount,
        t.type,
        t.created_at,
        t.sender_id,
        t.receiver_id,
        sender.fullname AS sender_name,
        receiver.fullname AS receiver_name
    FROM
        transactions t
    LEFT JOIN
        users sender ON t.sender_id = sender.id
    LEFT JOIN
        users receiver ON t.receiver_id = receiver.id
    WHERE
        (t.sender_id = ? AND t.type = 'sent')
    OR
        (t.receiver_id = ? AND t.type = 'received')
    ORDER BY
        t.created_at DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Failed to prepare statement: ' . $conn->error]);
    exit();
}

$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$transactions = [];
while ($row = $result->fetch_assoc()) {
    $transactions[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($transactions);
?>