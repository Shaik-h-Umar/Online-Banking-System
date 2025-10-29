<?php
session_start();
require "db_config.php";

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM transactions WHERE sender_id = '$user_id' OR receiver_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$rows = [];
while($row = mysqli_fetch_assoc($result)){
    $rows[] = $row;
}

echo json_encode($rows);
?>
