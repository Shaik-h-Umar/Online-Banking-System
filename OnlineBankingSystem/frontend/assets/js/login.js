// Password Toggle Logic
document.getElementById("toggle-password").addEventListener("click", function () {
    const password = document.getElementById("password");
    if (password.type === "password") {
        password.type = "text";
        this.classList.remove("fa-eye-slash");
        this.classList.add("fa-eye");
    } else {
        password.type = "password";
        this.classList.add("fa-eye-slash");
        this.classList.remove("fa-eye");
    }
});
