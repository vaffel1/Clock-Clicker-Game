<!-- nav.php - Navigation bar for the application -->

<!-- Define the navigation bar structure -->
<nav class="topnav">
    <!-- Left box (empty, likely used for styling or future features) -->
    <div class="left-box"></div>
    
    <!-- Center box containing links to main pages -->
    <div class="center-box">
        <!-- Link to the home page -->
        <a href="index.php" class="home-link">Home</a>
        
        <!-- Link to open the leaderboard, but currently doesn't do anything -->
        <a href="#" onclick="openLeaderboard()">Leaderboard</a>
        
        <!-- Link to open the info page, but currently doesn't do anything -->
        <a href="#" onclick="openInfo()">Info</a>
    </div>
    
    <!-- Right box containing additional links and user information -->
    <div class="right-box">
        <?php
        // Check if the user is logged in by checking for a session variable
        if (isset($_SESSION['user'])): ?>
            <!-- Display the logged-in user's username -->
            <span><?php echo htmlspecialchars($_SESSION['user']); ?></span>
        <?php else: ?>
            <!-- Display "Log In" instead of the username if the user is not logged in -->
            <span>Log In</span>
        <?php endif; ?>
        
        <!-- Link to the account page -->
        <a href="account.php" class="account-link">
            <!-- SVG image for the account link -->
            <img src="assets/account.svg" alt="Account">
        </a>
    </div>
</nav>

<!-- Define a media query for mobile devices (max-width: 550px) -->
<style>
@media (max-width: 550px) {
    /* Hide the username in the right box on mobile devices */
    .right-box span {
        display: none;
    }
}
</style>
