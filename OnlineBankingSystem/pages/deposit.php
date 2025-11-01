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

$current_balance = $user['balance'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Money - SecureBank</title>

    <link rel="stylesheet" href="../frontend/assets/css/deposit.css">
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
            <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="transfer.php"><i class="fas fa-paper-plane"></i> Transfer Money</a></li>
            <li><a href="transaction_history.php"><i class="fas fa-history"></i> Transaction History</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li class="active"><a href="deposit.php"><i class="fas fa-coins"></i> Deposit Money</a></li>
        </ul>
    </nav>
</aside>

<main class="main-content">
    <h1>Deposit Funds</h1>
    <p class="subtitle">Add money to your bank account safely.</p>

    <?php if(isset($_GET['success'])): ?>
        <div class="alert success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>
    <?php if(isset($_GET['error'])): ?>
        <div class="alert error"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <section class="card">
        <h2>Deposit Form</h2>
        <form method="POST" action="../backend/deposit_process.php">
            <div class="form-group">
                <label>Current Balance</label>
                <input type="text" disabled value="<?php echo $current_balance; ?>">
            </div>

            <div class="form-group">
                <label for="amount">Deposit Amount</label>
                <input type="number" id="amount" name="amount" placeholder="Enter amount" required>
            </div>

            <button type="submit" class="btn btn-primary btn-submit">Deposit Now</button>
        </form>
    </section>
</main>

<script src="../frontend/assets/js/deposit.js"></script>

</body>
</html>
