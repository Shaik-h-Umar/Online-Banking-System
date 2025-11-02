document.addEventListener('DOMContentLoaded', function () {

    // --- Live Search Functionality ---
    const searchInput = document.getElementById('user-search');
    const userTableBody = document.getElementById('user-table-body');
    const tableRows = userTableBody.getElementsByTagName('tr');

    if (searchInput) {
        searchInput.addEventListener('keyup', function () {
            const searchTerm = searchInput.value.toLowerCase();

            for (let i = 0; i < tableRows.length; i++) {
                const row = tableRows[i];
                const name = row.cells[0].textContent.toLowerCase();
                const accountNumber = row.cells[2].textContent.toLowerCase();
                
                if (name.includes(searchTerm) || accountNumber.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    }

    // --- Copy to Clipboard and Toast ---
    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.textContent = message;
        document.body.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);

        // Animate out and remove
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 2000);
    }

    if (userTableBody) {
        userTableBody.addEventListener('click', function (e) {
            // Use event delegation to catch clicks on copy buttons
            const button = e.target.closest('.copy-btn');
            if (button) {
                const accountNumber = button.getAttribute('data-account-number');

                if (accountNumber) {
                    navigator.clipboard.writeText(accountNumber).then(function () {
                        showToast('Account number copied!');
                    }, function (err) {
                        showToast('Failed to copy!');
                        console.error('Could not copy text: ', err);
                    });
                }
            }
        });
    }
});