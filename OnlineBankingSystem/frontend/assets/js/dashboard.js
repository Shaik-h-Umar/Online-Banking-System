fetch("../backend/dashboard_process.php")
    .then(res => res.json())
    .then(data => {

        // Update balance
        document.querySelector(".balance-amount").innerText = "₹" + data.balance;

        // Update stats
        document.getElementById("sentVal").innerText = "₹" + data.sent;
        document.getElementById("recvVal").innerText = "₹" + data.received;
        document.getElementById("txnVal").innerText = data.total_txn;

        // Transactions
        const box = document.querySelector(".transactions-content");
        box.innerHTML = "";

        if (data.recent.length === 0) {
            box.innerHTML = "<p>No recent transactions.</p>";
            return;
        }

        let html = "<table><tr><th>ID</th><th>Amount</th><th>Type</th><th>Date</th></tr>";
        data.recent.forEach(t => {
            html += `<tr>
                        <td>${t.id}</td>
                        <td>₹${t.amount}</td>
                        <td>${t.type}</td>
                        <td>${t.created_at}</td>
                    </tr>`;
        });
        html += "</table>";

        box.innerHTML = html;
    });
