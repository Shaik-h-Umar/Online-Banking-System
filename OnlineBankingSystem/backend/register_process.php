<?php
session_start();
require_once "db_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: ../pages/register.php");
        exit();
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $_SESSION['error'] = "Email already registered!";
        header("Location: ../pages/register.php");
        exit();
    }
    $stmt->close();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $initial_balance = 0.00;

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert user without account number first
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, mobile, password, balance) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssd", $full_name, $email, $mobile, $hashedPassword, $initial_balance);
        $stmt->execute();

        // Get the new user's ID
        $new_user_id = $conn->insert_id;

        // Generate account number based on the new ID
        $account_number = 'SB' . str_pad($new_user_id, 8, '0', STR_PAD_LEFT);

        // Update the user record with the new account number
        $stmt_update = $conn->prepare("UPDATE users SET account_number = ? WHERE id = ?");
        $stmt_update->bind_param("si", $account_number, $new_user_id);
        $stmt_update->execute();

        // Commit the transaction
        $conn->commit();

        // Set session variables and redirect
        $_SESSION['user_id'] = $new_user_id;
        $_SESSION['fullname'] = $full_name;
        $_SESSION['email'] = $email;
        $_SESSION['account_number'] = $account_number;
        
        header("Location: ../pages/dashboard.php");
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        // Log the error for debugging, but show a generic message to the user
        // error_log($e->getMessage());
        $_SESSION['error'] = "Something went wrong during registration!";
        header("Location: ../pages/register.php");
        exit();
    } finally {
        if (isset($stmt)) $stmt->close();
        if (isset($stmt_update)) $stmt_update->close();
        $conn->close();
    }
}
?>