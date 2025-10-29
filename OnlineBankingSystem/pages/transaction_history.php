<?php
// session_start(); // Uncomment when backend added
// if(!isset($_SESSION['user'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - SecureBank</title>

    <link rel="stylesheet" href="../frontend/assets/css/transaction_history.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

    <header class="app-header">
        <div class="header-left">
            <a href="#" class="logo">SecureBank</a>
            <nav class="header-nav">
                <a href="dashboard.php"><i class="fas fa-home nav-icon"></i> Dashboard</a>
                <a href="transfer.php"><i class="fas fa-chevron-right"></i> <i class="fas fa-paper-plane nav-icon"></i> Transfer Money</a>
                <a href="#" class="active"><i class="fas fa-chevron-right"></i> <i class="fas fa-history nav-icon"></i> Transaction History</a>
                <a href="profile.php"><i class="fas fa-chevron-right"></i> <i class="fas fa-user nav-icon"></i> Profile</a>
            </nav>
        </div>
        <div class="header-right">
            <span class="welcome-text">Welcome, Shaikh</span>
            <a href="#" class="btn btn-outline">Logout</a>
        </div>
    </header>

    <aside class="sidebar">
        <nav>
            <ul>
                <li>
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
                <li class="active">
                    <a href="#">
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
        <h1>Transaction History</h1>
        <p class="subtitle">View and manage your transaction records</p>

        <div class="filters-section">
            <div class="filter-group">
                <label>Date Range</label>
                <select id="date-range">
                    <option>This Month</option>
                    <option>Last Month</option>
                    <option>Last 3 Months</option>
                    <option>Last 6 Months</option>
                    <option>This Year</option>
                    <option>All Time</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Type</label>
                <select id="type">
                    <option>All Types</option>
                    <option>Money Sent</option>
                    <option>Money Received</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Search</label>
                <input type="text" id="search" placeholder="Search transactions...">
            </div>

            <div class="filter-buttons">
                <button class="btn btn-outline" id="clearFilters">Clear</button>
                <button class="btn btn-primary" id="exportBtn">Export</button>
            </div>
        </div>

        <section class="stats-grid">
            <div class="stat-card">
                <h3>Total Transactions</h3>
                <p class="amount" id="totalTransactions">0</p>
            </div>
            <div class="stat-card money-sent">
                <h3>Money Sent</h3>
                <p class="amount" id="moneySent">$0.00</p>
            </div>
            <div class="stat-card money-received">
                <h3>Money Received</h3>
                <p class="amount" id="moneyReceived">$0.00</p>
            </div>
            <div class="stat-card net-amount">
                <h3>Net Amount</h3>
                <p class="amount" id="netAmount">$0.00</p>
            </div>
        </section>

        <section class="transaction-list-container" id="transactionList">
            <div class="empty-transactions">
                <div class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h3>No transactions found</h3>
                <p>No transactions match your current filters</p>
                <button class="btn btn-primary" id="clearFilters2">Clear Filters</button>
            </div>
        </section>

    </main>

    <div class="fab-chat" title="Help"><i class="fas fa-comment-dots"></i></div>
    <div class="fab-toggle" title="Toggle Dark Mode"><i class="fas fa-moon"></i></div>

    <script src="../frontend/assets/js/transaction_history.js"></script>
</body>
</html>
