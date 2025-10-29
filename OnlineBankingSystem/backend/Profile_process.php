<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if ($new !== $confirm) {
        echo "<script>alert('Password mismatch!'); window.location='profile.php';</script>";
        exit;
    }

    // TODO: connect DB and verify current password
    // Update password query goes here

    echo "<script>alert('Password updated successfully!'); window.location='profile.php';</script>";
}
?>
