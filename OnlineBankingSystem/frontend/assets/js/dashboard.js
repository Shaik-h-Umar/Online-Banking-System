// Quick action buttons
document.querySelector('.send-money').onclick = () => {
    window.location.href = "transfer.php";
};

document.querySelector('.view-history').onclick = () => {
    window.location.href = "history.php";
};

document.querySelector('.settings').onclick = () => {
    window.location.href = "profile.php";
};

// Floating Buttons
document.querySelector('.fab-chat').onclick = () => {
    alert("Chat support coming soon!");
};

document.querySelector('.fab-toggle').onclick = () => {
    document.body.classList.toggle("dark-mode");
};
