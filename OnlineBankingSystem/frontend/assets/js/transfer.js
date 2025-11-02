// transfer.js - lightweight validation + UX
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("transferForm");
  if (!form) return;

  const amountInput = document.getElementById("amount");
  const recipientInput = document.getElementById("recipient-account"); // This is now an <input type="text">
  const togglePassword = document.getElementById("toggle-password");
  const passwordInput = document.getElementById("password");

  // Toggle show/hide password
  if (togglePassword && passwordInput) {
    togglePassword.addEventListener("click", function () {
      const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);
      this.classList.toggle("fa-eye");
      this.classList.toggle("fa-eye-slash");
    });
  }

  // Basic client-side validation & confirm
  form.addEventListener("submit", function (e) {
    const amount = parseFloat((amountInput.value || "").replace(/[^0-9.]/g, ""));
    const recipient = recipientInput.value.trim(); // Get value from <input> and trim
    const pwd = passwordInput ? passwordInput.value.trim() : "";

    // --- UPDATED VALIDATION ---
    if (!recipient) { // Check if recipient input is empty
      alert("Please enter a recipient account number.");
      e.preventDefault();
      recipientInput.focus();
      return;
    }
    // --- END UPDATED VALIDATION ---

    if (!amount || isNaN(amount) || amount <= 0) {
      alert("Enter a valid amount greater than 0.");
      e.preventDefault();
      amountInput.focus();
      return;
    }
    if (!pwd) {
      alert("Enter your password to confirm.");
      e.preventDefault();
      passwordInput.focus();
      return;
    }

    // final confirm
    // Use the raw recipient value for confirmation
    const ok = confirm("Confirm transfer of ₹" + amount.toFixed(2) + " to " + recipient + "?");
    if (!ok) {
      e.preventDefault();
    }
  });
});