// ✅ Show/Hide Password Toggle
document.querySelectorAll(".toggle-password").forEach(icon => {
    icon.addEventListener("click", function () {
        let target = document.getElementById(this.getAttribute("data-target"));
        if (target.type === "password") {
            target.type = "text";
            this.classList.replace("fa-eye-slash", "fa-eye");
        } else {
            target.type = "password";
            this.classList.replace("fa-eye", "fa-eye-slash");
        }
    });
});

// ✅ Password Match Validation Before Form Submit
document.querySelector("form").addEventListener("submit", function (e) {
    const pass = document.getElementById("password").value;
    const confirm_pass = document.getElementById("confirm-password").value;

    if (pass !== confirm_pass) {
        e.preventDefault();
        alert("❌ Passwords do not match!");
    }
});
