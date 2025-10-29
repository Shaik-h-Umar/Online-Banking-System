// Clear filter buttons
document.getElementById("clearFilters")?.addEventListener("click", () => {
    document.getElementById("date-range").selectedIndex = 0;
    document.getElementById("type").selectedIndex = 0;
    document.getElementById("search").value = "";
});

document.getElementById("clearFilters2")?.addEventListener("click", () => {
    document.getElementById("date-range").selectedIndex = 0;
    document.getElementById("type").selectedIndex = 0;
    document.getElementById("search").value = "";
});

// Export placeholder
document.getElementById("exportBtn")?.addEventListener("click", () => {
    alert("Export feature will be available after backend integration.");
});

// Dark mode placeholder
document.querySelector(".fab-toggle")?.addEventListener("click", () => {
    alert("Dark mode coming soon.");
});
