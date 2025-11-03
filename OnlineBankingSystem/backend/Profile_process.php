<?php
session_start();

// 1. Only logged-in users can change password (session check required).
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php?error=Please login to your account");
    exit();
}

// 11. If someone tries to access this file without POST, redirect with an error.
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../pages/profile.php?error=Invalid request");
    exit();
}

require_once '../backend/db_config.php';

// 9. All user input must be trimmed and validated.
$current_password = trim($_POST['current_password']);
$new_password = trim($_POST['new_password']);
$confirm_password = trim($_POST['confirm_password']);
$user_id = $_SESSION['user_id'];

if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    header("Location: ../pages/profile.php?error=All fields are required");
    exit();
}

// 10. Password must be at least 6 characters.
if (strlen($new_password) < 6) {
    header("Location: ../pages/profile.php?error=New password must be at least 6 characters long");
    exit();
}

// 4. The new password must match confirm password.
if ($new_password !== $confirm_password) {
    header("Location: ../pages/profile.php?error=New password and confirm password do not match");
    exit();
}

// 2. Fetch the current hashed password of the logged-in user from the database.
$sql = "SELECT password FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    header("Location: ../pages/profile.php?error=Database error: could not prepare statement");
    exit();
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: ../pages/profile.php?error=User not found");
    exit();
}

$row = $result->fetch_assoc();
$hashed_password_from_db = $row['password'];
$stmt->close();

// 3. Verify if the entered current password is correct using password_verify().
if (!password_verify($current_password, $hashed_password_from_db)) {
    header("Location: ../pages/profile.php?error=Incorrect current password");
    exit();
}

// 6. Do NOT allow the user to set the same password again.
if (password_verify($new_password, $hashed_password_from_db)) {
    header("Location: ../pages/profile.php?error=New password cannot be the same as the old password");
    exit();
}

// 5. The new password must be hashed using password_hash() before saving.
$new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// 12. Use prepared statements (mysqli).
$sql_update = "UPDATE users SET password = ? WHERE id = ?";
$stmt_update = $conn->prepare($sql_update);
if ($stmt_update === false) {
    header("Location: ../pages/profile.php?error=Database error: could not prepare update statement");
    exit();
}

$stmt_update->bind_param("si", $new_hashed_password, $user_id);

if ($stmt_update->execute()) {
    // 7. On success redirect to: ../pages/profile.php?success=Password updated successfully
    header("Location: ../pages/profile.php?success=Password updated successfully");
    exit();
} else {
    // 8. On any error redirect using GET query:
    header("Location: ../pages/profile.php?error=Failed to update password");
    exit();
}

$stmt_update->close();
$conn->close();

?>