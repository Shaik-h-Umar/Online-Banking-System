document.addEventListener("DOMContentLoaded", () => {
    const amount = document.getElementById("amount");

    amount.addEventListener("input", () => {
        if (amount.value < 1) {
            amount.style.border = "2px solid red";
        } else {
            amount.style.border = "";
        }
    });
});
