const notyf = new Notyf({
    duration: 3000,
    position: {
        x: 'right',
        y: 'top',
    }
});
document.querySelectorAll('.copy-button').forEach(button => {
    button.addEventListener('click', event => {
        const input = event.target.previousElementSibling;
        input.select();
        document.execCommand('copy');
        notyf.success("Copied to clipboard!");
    });
});
async function update_leaderboard() {
    const response = await fetch('/controller/apis/leaderboard');
    const leaderboard = await response.json();
    const leaderboardContainer = document.getElementById('leaderboard-container');
    leaderboardContainer.innerHTML = '';
    if (leaderboard && Object.keys(leaderboard).length > 0) {
        let sortedLeaderboard = Object.entries(leaderboard)
            .sort((a, b) => b[1] - a[1])
            .slice(0, 5);
        let rank = 1;
        for (const [sitename, amount] of sortedLeaderboard) {
            const leaderItem = document.createElement('div');
            leaderItem.classList.add('leader-item');
            leaderItem.innerHTML = `
                    <div><span class="cookie-icon"></span> ${rank}. ${sitename}</div>
                    <span>${amount} Cookies</span>
                `;
            leaderboardContainer.appendChild(leaderItem);
            rank++;
        }
    } else {
        leaderboardContainer.innerHTML = '<p>No leaderboard data available.</p>';
    }
}
update_leaderboard();
setInterval(() => {
    update_leaderboard();
}, 5000);
const profileIcon = document.querySelector(".profile-icon");
const dropdown = document.querySelector(".profile-dropdown");
profileIcon.addEventListener("click", function (event) {
    event.stopPropagation();
    dropdown.classList.toggle("show");
});
document.addEventListener("click", function (event) {
    if (!profileIcon.contains(event.target)) {
        dropdown.classList.remove("show");
    }
});