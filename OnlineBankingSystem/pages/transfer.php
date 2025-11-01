<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Transfer Money - SecureBank</title>

<link rel="stylesheet" href="../frontend/assets/css/transfer.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

    <header class="app-header">
        <div class="header-left">
            <a href="#" class="logo">SecureBank</a>
            <nav class="header-nav">
                <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="transfer.php" class="active"><i class="fas fa-chevron-right"></i> <i class="fas fa-paper-plane"></i> Transfer Money</a>
                <a href="transaction_history.php"><i class="fas fa-chevron-right"></i> <i class="fas fa-history"></i> Transaction History</a>
                <a href="profile.php"><i class="fas fa-chevron-right"></i> <i class="fas fa-user"></i> Profile</a>
            </nav>
        </div>
        <div class="header-right">
            <span class="welcome-text">Welcome, <?php echo $_SESSION['fullname']; ?></span>
            <a href="../backend/logout.php" class="btn btn-outline">Logout</a>
        </div>
    </header>

    <aside class="sidebar">
        <nav>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="active"><a href="transfer.php"><i class="fas fa-paper-plane"></i> Transfer Money</a></li>
                <li><a href="transaction_history.php"><i class="fas fa-history"></i> Transaction History</a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <h1>Transfer Money</h1>
        <p class="subtitle">Send money securely to any account</p>

        <!-- show success / error at top -->
        <?php if($success === '1'): ?>
            <div style="padding:12px;border-radius:8px;background:#e6ffef;border:1px solid #b7f0d0;color:#176f3d;margin-bottom:18px;">
                Transfer successful.
            </div>
        <?php elseif($error !== ''): ?>
            <div style="padding:12px;border-radius:8px;background:#fff0f0;border:1px solid #f2c2c2;color:#9a1b1b;margin-bottom:18px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="transfer-layout">
            <div class="transfer-form-container">
                <div class="card transfer-form">
                    <div class="card-header">
                        <h2>Transfer Details</h2>
                    </div>
                    <p class="card-subtitle">Enter the recipient information and amount</p>

                    <form id="transferForm" action="../backend/transfer_process.php" method="POST" novalidate>
                        <div class="form-group">
                            <label for="recipient-account">Recipient Account Number</label>
                            <input type="text" name="receiver" id="recipient-account" placeholder="Enter recipient's account number" required>
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="text" name="amount" id="amount" placeholder="0.00" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description (Optional)</label>
                            <input type="text" name="desc" id="description" placeholder="What's this transfer for?">
                        </div>

                        <div class="form-group">
                            <label for="password">Confirm with Password</label>
                            <div class="password-wrapper">
                                <input type="password" name="password" id="password" placeholder="Enter your password to confirm" required>
                                <i class="fas fa-eye-slash" id="toggle-password"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-submit">Transfer Money</button>
                    </form>
                </div>
            </div>

            <aside class="transfer-sidebar">
                <div class="card account-card">
                    <div class="card-header">
                        <h3>Your Account</h3>
                        <i class="fas fa-credit-card card-icon"></i>
                    </div>
                    <p class="balance-label">Available Balance</p>
                    <div class="balance-amount">$0.00</div>
                    <hr class="account-divider">
                    <p class="account-number">****-****-****-9406</p>
                </div>

                <div class="card limits-card">
                    <div class="card-header"><h3>Transfer Limits</h3></div>
                    <div class="limit-item"><span class="limit-label">Daily Limit</span><span class="limit-amount">$10,000</span></div>
                    <div class="limit-item"><span class="limit-label">Monthly Limit</span><span class="limit-amount">$100,000</span></div>
                </div>

                <div class="security-notice">
                    <div class="icon"><i class="fas fa-lock"></i></div>
                    <div class="text-content">
                        <h4>Security Notice</h4>
                        <p>All transfers are encrypted and monitored for security. You'll receive a confirmation once the transfer is complete.</p>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <div class="fab-chat" title="Help"><i class="fas fa-comment-dots"></i></div>
    <div class="fab-toggle" title="Toggle Dark Mode"><i class="fas fa-moon"></i></div>

<script src="../frontend/assets/js/transfer.js"></script>
</body>
</html>
