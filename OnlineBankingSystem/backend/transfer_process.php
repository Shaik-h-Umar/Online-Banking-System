<?php
// transfer_process.php - simulated server-side (NO DB)
session_start();

// simple server-side validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: transfer.php');
    exit();
}

$receiver = isset($_POST['receiver']) ? trim($_POST['receiver']) : '';
$amount = isset($_POST['amount']) ? trim($_POST['amount']) : '';
$desc = isset($_POST['desc']) ? trim($_POST['desc']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// validate
if ($receiver === '' || strlen($receiver) < 4) {
    header('Location: transfer.php?error=' . urlencode('Invalid receiver account number.'));
    exit();
}

$amountClean = floatval(preg_replace('/[^0-9.]/', '', $amount));
if ($amountClean <= 0) {
    header('Location: transfer.php?error=' . urlencode('Enter a valid amount.'));
    exit();
}

if ($password === '') {
    header('Location: transfer.php?error=' . urlencode('Password required to confirm transfer.'));
    exit();
}

// Simulate success (no DB)
 // In the real app you'd check password, balance and perform DB transaction here.
header('Location: transfer.php?success=1');
exit();
