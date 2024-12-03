<?php
/**
 * index.php - Index page for the Clock Clicker Game.
 *
 * This script starts a PHP session and includes other HTML files to render the game's layout and navigation.
 */

// Start the session, which allows PHP to store and manage data between HTTP requests.
session_start();
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8"> <!-- Character encoding for the document -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsive design settings -->
    <title>Clock Clicker Game</title> <!-- Page title -->
    <link rel="icon" href="assets/clock.svg" type="image/svg+xml">
    
    <!-- Link to external CSS files for styling -->
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/navstyle.css">
    <link rel="stylesheet" href="styles/infostyle.css">
</head>
<body>
    <!-- Top Navigation (included from nav.php) -->
    <?php include 'nav.php'; ?>

    <!-- Display information page (included from info.php) -->
    <?php include 'info.php'; ?>
    
    <!-- Game container with clock display, multiplier, and time stats -->
    <div id="game-container">
        <h1>Clock Clicker Game</h1>
        <p>Multiplier:  <span id="multiplier" class="time-update">1</span>x</p>
        <div id="clock" class="clock">
            <!-- Clock SVG image -->
            <img src="assets/clock.svg" alt="Clock">
        </div>
        <p>Total Time:  <span id="total-time" class="time-update">0</span> s</p>
        <p>Best Time:  <span id="best-time" class="time-update">0</span> s</p>
    </div>

    <!-- Audio for click sound effects -->
    <audio id="clickSound" src="assets/tick.mp3"></audio>
    
    <!-- Leaderboard container (hidden by default) -->
    <div id="leaderboard-container" style="display:none;">
        <?php include 'leaderboard.php'; ?>
    </div>
    
    <!-- Include JavaScript file for game logic and functionality -->
    <script src="script.js"></script>
</body>
</html>
