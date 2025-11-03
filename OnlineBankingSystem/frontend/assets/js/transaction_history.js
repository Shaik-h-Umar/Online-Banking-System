document.addEventListener('DOMContentLoaded', function () {
    const transactionList = document.getElementById('transactionList');
    const emptyTransactionsView = document.querySelector('.empty-transactions');

    // Stats elements
    const totalTransactionsEl = document.getElementById('totalTransactions');
    const moneySentEl = document.getElementById('moneySent');
    const moneyReceivedEl = document.getElementById('moneyReceived');
    const netAmountEl = document.getElementById('netAmount');

    // Show loading state
    transactionList.innerHTML = '<div class="loading-state"><i class="fas fa-spinner fa-spin"></i>Loading transactions...</div>';
    emptyTransactionsView.style.display = 'none';

    fetch('../backend/transaction_history_process.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Network response was not ok: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            // Clear loading state
            transactionList.innerHTML = '';

            if (data.error) {
                console.error('Error from server:', data.error);
                transactionList.innerHTML = `<div class="empty-transactions" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; text-align: center;"><div class="icon" style="font-size: 60px; color: #E3E8F0;"><i class="fas fa-exclamation-circle"></i></div><h3>Error Loading Data</h3><p>${data.error}</p></div>`;
                return;
            }

            if (data.length === 0) {
                transactionList.appendChild(emptyTransactionsView);
                emptyTransactionsView.style.display = 'flex';
                // Reset stats to zero
                totalTransactionsEl.textContent = '0';
                moneySentEl.textContent = '$0.00';
                moneyReceivedEl.textContent = '$0.00';
                netAmountEl.textContent = '$0.00';
            } else {
                emptyTransactionsView.style.display = 'none';

                let totalTransactions = data.length;
                let moneySent = 0;
                let moneyReceived = 0;

                transactionList.style.display = 'block';

                data.forEach(tx => {
                    const amount = parseFloat(tx.amount);
                    if (tx.type === 'sent') {
                        moneySent += amount;
                    } else if (tx.type === 'received') {
                        moneyReceived += amount;
                    }

                    const card = document.createElement('div');
                    card.className = `transaction-item card ${tx.type}`;

                    const iconClass = tx.type === 'sent' ? 'fa-arrow-up' : 'fa-arrow-down';
                    const amountPrefix = tx.type === 'sent' ? '-' : '+';
                    const name = tx.type === 'sent' ? (tx.receiver_name || 'N/A') : (tx.sender_name || 'N/A');
                    const title = tx.type === 'sent' ? `Sent to ${name}` : `Received from ${name}`;
                    
                    const formattedDate = new Date(tx.created_at).toLocaleString('en-US', {
                        year: 'numeric', month: 'long', day: 'numeric',
                        hour: '2-digit', minute: '2-digit'
                    });

                    // Using inline styles to avoid modifying CSS files, as per instructions.
                    card.innerHTML = `
                        <div style="display: flex; align-items: center; justify-content: space-between; padding: 15px 20px; border-bottom: 1px solid #E9ECEF;">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <i class="fas ${iconClass}" style="font-size: 20px; color: ${tx.type === 'sent' ? '#EB5757' : '#2DCE89'};"></i>
                                <div>
                                    <h4 style="font-weight: 600; font-size: 16px; margin: 0;">${title}</h4>
                                    <p style="font-size: 14px; color: #718096; margin: 0;">${formattedDate}</p>
                                </div>
                            </div>
                            <div style="font-weight: 600; font-size: 16px; color: ${tx.type === 'sent' ? '#EB5757' : '#2DCE89'};">
                                ${amountPrefix}$${amount.toFixed(2)}
                            </div>
                        </div>
                    `;
                    transactionList.appendChild(card);
                });

                // Update stats
                totalTransactionsEl.textContent = totalTransactions;
                moneySentEl.textContent = `$${moneySent.toFixed(2)}`;
                moneyReceivedEl.textContent = `$${moneyReceived.toFixed(2)}`;
                const netAmount = moneyReceived - moneySent;
                netAmountEl.textContent = `${netAmount < 0 ? '-' : ''}$${Math.abs(netAmount).toFixed(2)}`;
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            transactionList.innerHTML = '';
            transactionList.appendChild(emptyTransactionsView);
            emptyTransactionsView.style.display = 'flex';
            emptyTransactionsView.querySelector('h3').textContent = 'Connection Error';
            emptyTransactionsView.querySelector('p').textContent = 'Could not fetch transaction history. Please check your connection.';
        });
});