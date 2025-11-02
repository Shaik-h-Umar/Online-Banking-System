<?php
session_start();
require_once "../backend/db_config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$balance = 0.00;
$account_number = $_SESSION['account_number'] ?? 'N/A';

// Fetch the current user's balance
$stmt = $conn->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $balance = $row['balance'] ?? 0.00;
    }
}
$stmt->close();

// Fetch all other users for the recipient datalist
$other_users = [];
$stmt = $conn->prepare("SELECT fullname, account_number FROM users WHERE id != ? ORDER BY fullname ASC");
$stmt->bind_param("i", $user_id);
if ($stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $other_users[] = $row;
    }
}
$stmt->close();

$success = $_GET['success'] ?? '';
$error   = $_GET['error']   ?? '';
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
            <a href="transfer.php" class="active"><i class="fas fa-paper-plane"></i> Transfer Money</a>
            <a href="transaction_history.php"><i class="fas fa-history"></i> Transaction History</a>
            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
        </nav>
    </div>
    <div class="header-right">
        <span class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?></span>
        <a href="../backend/logout.php" class="btn btn-outline">Logout</a>
    </div>
</header>

<aside class="sidebar">
    <nav>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="active"><a href="transfer.php"><i class="fas fa-paper-plane"></i> Transfer Money</a></li>
            <li><a href="transaction_history.php"><i class="fas fa-history"></i> Transaction History</a></li>
            <li><a href="deposit.php"><i class="fas fa-money-bill-wave"></i> <span>Deposit Money</span></a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="users.php"><i class="fas fa-users"></i><span>All Users</span></a></li>
        </ul>
    </nav>
</aside>

<main class="main-content">
    <h1>Transfer Money</h1>
    <p class="subtitle">Send money securely to any account</p>

    <?php
    // Display standard success/error flash messages
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        $flash_class = $flash['type'] === 'error' ? 'alert error' : 'alert success';
        echo "<div class='{$flash_class}'>" . htmlspecialchars($flash['message']) . "</div>";
        unset($_SESSION['flash_message']);
    }
    ?>

    <div class="transfer-layout">
        <div class="transfer-form-container">
            <div class="card transfer-form">
                <div class="card-header">
                    <h2>Transfer Details</h2>
                </div>
                <p class="card-subtitle">Enter recipient account number and amount</p>

                <form id="transferForm" action="../backend/transfer_process.php" method="POST" novalidate>
                    <div class="form-group">
                        <label for="recipient-account">Recipient</label>
                        <input type="text" name="receiver" id="recipient-account" placeholder="Enter or select recipient" list="recipient-suggestions" required>
                        <datalist id="recipient-suggestions">
                            <?php foreach ($other_users as $user): ?>
                                <option value="<?php echo htmlspecialchars($user['account_number']); ?>">
                                    <?php echo htmlspecialchars($user['fullname']); ?>
                                </option>
                            <?php endforeach; ?>
                        </datalist>
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" name="amount" id="amount" placeholder="Enter amount" required step="0.01" min="0.01">
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
                <div class="balance-amount">₹<?php echo number_format($balance, 2); ?></div>
                <hr class="account-divider">
                <p class="account-number"><?php echo htmlspecialchars($account_number); ?></p>
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

<script src="../frontend/assets/js/transfer.js?v=<?php echo time(); ?>"></script>
</body>
</html>