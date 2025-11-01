<?php
session_start();

// If already logged in, redirect
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SecureBank</title>

    <link rel="stylesheet" href="../frontend/assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

    <div class="login-card">
        <a href="index.php" class="logo">SecureBank</a>
        <h2>Welcome Back</h2>
        <p class="subtitle">Sign in to your account to continue</p>

        <?php if($error != ""): ?>
            <p style="color: red; font-size:14px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form class="login-form" action="../backend/login_process.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    <i class="fas fa-eye-slash" id="toggle-password"></i>
                </div>
            </div>

            <div class="form-options">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="#" class="forgot-link">Forgot password?</a>
            </div>

            <button type="submit" class="btn btn-primary">Sign In</button>
        </form>

        <div class="signup-link">
            <p>Don't have an account? <a href="register.php">Create one</a></p>
        </div>
    </div>

    <script src="../frontend/assets/js/login.js"></script>

</body>
</html>
