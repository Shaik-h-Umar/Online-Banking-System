<?php
session_start();
require_once '../backend/db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$name = $user['fullname'];
$email = $user['email'];
$mobile = $user['mobile'];
$acc = '****-****-****-' . substr($user['account_number'], -4);
$type = "Savings Account"; // Assuming a default value
$joined = date("F j, Y", strtotime($user['created_at']));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - SecureBank</title>

    <link rel="stylesheet" href="../frontend/assets/css/profile.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>

    <header class="app-header">
        <a href="#" class="logo">SecureBank</a>
        <div class="header-right">
            <span class="welcome-text">Welcome, <?php echo $_SESSION['fullname']; ?></span>
            <a href="../backend/logout.php" class="btn btn-outline">Logout</a>
        </div>
    </header>

    <aside class="sidebar">
        <nav>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                <li><a href="transfer.php"><i class="fas fa-paper-plane"></i><span>Transfer Money</span></a></li>
                <li><a href="transaction_history.php"><i class="fas fa-history"></i><span>Transaction History</span></a></li>
                <li class="active"><a href="profile.php"><i class="fas fa-user"></i><span>Profile</span></a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <h1>Your Profile</h1>
        <p class="subtitle">Manage your personal information and security settings.</p>

        <?php if(isset($_GET['success'])): ?>
            <div class="alert success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        <?php if(isset($_GET['error'])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>

        <section class="card">
            <h2>Personal Information</h2>
            <form>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" value="<?php echo $name; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" value="<?php echo $email; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="text" value="<?php echo $mobile; ?>" disabled>
                </div>
            </form>
        </section>

        <section class="card">
            <h2>Account Details</h2>
            <form>
                <div class="form-group">
                    <label>Account Number</label>
                    <input type="text" value="<?php echo $acc; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Account Type</label>
                    <input type="text" value="<?php echo $type; ?>" disabled>
                </div>
                <div class="form-group">
                    <label>Joined On</label>
                    <input type="text" value="<?php echo $joined; ?>" disabled>
                </div>
            </form>
        </section>
        
        <section class="card">
            <h2>Change Password</h2>
            <form action="../backend/profile_process.php" method="POST">
                <div class="form-group">
                    <label>Current Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="current_password" required>
                        <i class="fas fa-eye-slash toggle-password"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="new_password" required>
                        <i class="fas fa-eye-slash toggle-password"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="confirm_password" required>
                        <i class="fas fa-eye-slash toggle-password"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-submit">Update Password</button>
            </form>
        </section>

    </main>

    <div class="fab-chat"><i class="fas fa-comment-dots"></i></div>
    <div class="fab-toggle"><i class="fas fa-moon"></i></div>

<script src="../frontend/assets/js/profile.js"></script>
</body>
</html>
