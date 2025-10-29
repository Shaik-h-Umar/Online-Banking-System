<?php
    // Start session (important for future success/error messages)
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - SecureBank</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../frontend/assets/css/register.css">
</head>

<body>

    <div class="create-account-card">
        <a href="index.php" class="logo">SecureBank</a>
        <h1>Create Your Account</h1>
        <p class="subtitle">Join thousands of satisfied customers</p>

        <!-- ✅ Display PHP error messages -->
        <?php if(isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php 
                    echo $_SESSION['error']; 
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="success-message">
                <?php 
                    echo $_SESSION['success']; 
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="../backend/register_process.php" method="POST">
            <div class="form-group">
                <label for="full-name">Full Name</label>
                <input type="text" id="full-name" name="fullname" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="mobile-number">Mobile Number</label>
                <input type="tel" id="mobile-number" name="mobile" placeholder="Enter your mobile number" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" placeholder="Create a strong password" required>
                    <i class="fas fa-eye-slash toggle-password" data-target="password"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>
                    <i class="fas fa-eye-slash toggle-password" data-target="confirm-password"></i>
                </div>
            </div>

            <div class="terms-checkbox">
                <input type="checkbox" id="agree-terms" required>
                <label for="agree-terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
            </div>

            <button type="submit" class="btn btn-primary">Create Account</button>
        </form>

        <div class="signin-link">
            <p>Already have an account? <a href="login.php">Sign in</a></p>
        </div>
    </div>

    <script src="../frontend/assets/js/register.js"></script>

</body>
</html>
