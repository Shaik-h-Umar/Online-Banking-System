fetch("../backend/dashboard_process.php")
    .then(res => res.json())
    .then(data => {

        // Format account number (e.g., 1234567890 -> 1234-5678-90)
        const formatAccountNumber = (num) => {
            if (!num || num === 'N/A') return 'N/A';
            const str = num.toString();
            if (str.length !== 10) return str; // Return as is if not 10 digits
            return `${str.substring(0, 4)}-${str.substring(4, 8)}-${str.substring(8, 10)}`;
        };

        // Update balance
        document.getElementById("balanceVal").innerText = "₹" + parseFloat(data.balance).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // Update Account Number
        const accountNumberEl = document.querySelector(".account-number");
        if (accountNumberEl) {
            accountNumberEl.innerText = "Account: " + formatAccountNumber(data.account_number);
        }

        // Update stats
        document.getElementById("sentVal").innerText = "₹" + parseFloat(data.sent).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById("recvVal").innerText = "₹" + parseFloat(data.received).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById("txnVal").innerText = data.total_txn;

        // Transactions
        const box = document.querySelector(".transactions-content");
        box.innerHTML = "";

        if (data.recent.length === 0) {
            const emptyState = `
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <h3>No transactions yet</h3>
                    <p>Your recent transactions will appear here.</p>
                    <a href="transfer.php" class="btn btn-primary">Make your first transfer</a>
                </div>`;
            box.innerHTML = emptyState;
            return;
        }

        let html = `
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>`;
        
        data.recent.forEach(t => {
            const transactionType = t.type === 'sent' ? 'Sent' : 'Received';
            const amountClass = t.type === 'sent' ? 'amount-sent' : 'amount-received';
            const amountSign = t.type === 'sent' ? '-' : '+';
            const formattedAmount = parseFloat(t.amount).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            const formattedDate = new Date(t.created_at).toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });

            html += `
                <tr>
                    <td><span class="transaction-type ${t.type}">${transactionType}</span></td>
                    <td class="${amountClass}">${amountSign} ₹${formattedAmount}</td>
                    <td>${t.description || 'N/A'}</td>
                    <td>${formattedDate}</td>
                </tr>`;
        });

        html += "</tbody></table></div>";
        box.innerHTML = html;
    });