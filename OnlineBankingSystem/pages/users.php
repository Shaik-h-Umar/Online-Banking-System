<?php
session_start();
require_once '../backend/db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_user_id = $_SESSION['user_id'];

// Fetch all users except the currently logged-in one
$stmt = $conn->prepare("SELECT fullname, email, account_number, balance FROM users WHERE id != ? ORDER BY fullname ASC");
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users - SecureBank</title>

    <!-- Common styles -->
    <link rel="stylesheet" href="../frontend/assets/css/profile.css"> <!-- Reusing profile styles for consistency -->
    <!-- Page-specific styles -->
    <link rel="stylesheet" href="../frontend/assets/css/users.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>

    <header class="app-header">
        <a href="#" class="logo">SecureBank</a>
        <div class="header-right">
            <span class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?></span>
            <a href="../backend/logout.php" class="btn btn-outline">Logout</a>
        </div>
    </header>

    <aside class="sidebar">
        <nav>
            <ul>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                <li><a href="transfer.php"><i class="fas fa-paper-plane"></i><span>Transfer Money</span></a></li>
                <li><a href="transaction_history.php"><i class="fas fa-history"></i><span>Transaction History</span></a></li>
                <li><a href="deposit.php"><i class="fas fa-money-bill-wave"></i><span>Deposit Money</span></a></li>
                <li><a href="profile.php"><i class="fas fa-user"></i><span>Profile</span></a></li>
                <li class="active"><a href="users.php"><i class="fas fa-users"></i><span>All Users</span></a></li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <h1>All Users</h1>
        <p class="subtitle">Find other users to transfer money to their accounts.</p>

        <section class="card">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="user-search" placeholder="Search by name or account number...">
                </div>
            </div>
            <div class="table-responsive">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Account Number</th>
                            <th>Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="user-table-body">
                        <?php if (count($users) > 0): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo htmlspecialchars($user['account_number']); ?></td>
                                    <td>₹<?php echo number_format($user['balance'], 2); ?></td>
                                    <td>
                                        <button class="copy-btn" data-account-number="<?php echo htmlspecialchars($user['account_number']); ?>">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem;">No other users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="../frontend/assets/js/users.js"></script>
</body>
</html>