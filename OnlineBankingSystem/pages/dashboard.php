<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SecureBank</title>

    <link rel="stylesheet" href="../frontend/assets/css/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>

    <header class="app-header">
        <a href="#" class="logo">SecureBank</a>
        <div class="header-right">
            <span class="welcome-text">Welcome, <?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User'; ?></span>
            <a href="../backend/logout.php" class="btn btn-outline">Logout</a>
        </div>
    </header>

    <aside class="sidebar">
        <nav>
            <ul>
                <li class="active">
                    <a href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="transfer.php">
                        <i class="fas fa-paper-plane"></i>
                        <span>Transfer Money</span>
                    </a>
                </li>
                <li>
                    <a href="transaction_history.php">
                        <i class="fas fa-history"></i>
                        <span>Transaction History</span>
                    </a>
                </li>
                <li>
                    <a href="profile.php">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <div class="greeting">
            <h1>Good evening, <?php echo isset($_SESSION['fullname']) ? $_SESSION['fullname'] : 'User'; ?>!</h1>
            <p>Here's what's happening with your account today.</p>
        </div>

        <section class="top-grid">
            <div class="card balance-card">
                <h2>Account Balance</h2>
                <div class="balance-amount" id="balanceVal">$0</div>

                <div class="account-number">Account: ****-****-****-9406</div>
                <i class="fas fa-wallet card-icon"></i>
            </div>

            <div class="card quick-actions">
                <div class="card-header">
                    <h2>Quick Actions</h2>
                    <i class="fas fa-bolt card-icon"></i>
                </div>
                <div class="action-buttons">
                    <a href="transfer.php" class="action-btn send-money">
                        <i class="fas fa-paper-plane"></i>
                        <span>Send Money</span>
                    </a>
                    <a href="transaction_history.php" class="action-btn view-history">
                        <i class="fas fa-history"></i>
                        <span>View History</span>
                    </a>
                    <a href="profile.php" class="action-btn settings">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </div>
            </div>

            <div class="card this-month">
                <div class="card-header">
                    <h2>This Month</h2>
                    <i class="fas fa-chart-pie card-icon"></i>
                </div>
                <ul class="stats-list">
                    <li>
                        <span class="stat-label">Money Sent</span>
                        <span class="stat-value" id="sentVal">$0</span>
<span class="stat-value" id="recvVal">$0</span>
<span class="stat-value" id="txnVal">0</span>
                    </li>
                    <li>
                        <span class="stat-label">Money Received</span>
                        <span class="stat-value" id="sentVal">$0</span>
<span class="stat-value" id="recvVal">$0</span>
<span class="stat-value" id="txnVal">0</span>

                    </li>
                    <li>
                        <span class="stat-label">Transactions</span>
                        <span class="stat-value">0</span>
                    </li>
                </ul>
            </div>
        </section>

        <section class="card transactions-card">
            <div class="card-header">
                <h2>Recent Transactions</h2>
                <a href="transaction_history.php" class="btn btn-outline">View All</a>
            </div>
            
            <div class="transactions-content">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h3>No transactions yet</h3>
                    <p>Your recent transactions will appear here.</p>
                    <a href="transfer.php" class="btn btn-primary">Make your first transfer</a>
                </div>
            </div>
        </section>
    </main>

    <div class="fab-chat" title="Help">
        <i class="fas fa-comment-dots"></i>
    </div>
    <div class="fab-toggle" title="Toggle Dark Mode">
        <i class="fas fa-moon"></i>
    </div>

    <script src="../frontend/assets/js/dashboard.js"></script>
</body>
</html>
