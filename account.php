<?php
/**
 * account.php: This file handles the account page of a user.
 *
 * It checks if the user is logged in, includes necessary configuration and authentication files,
 * and displays the account information and actions for the current user.
 */

// Include auth.php to check if user is logged in
require 'auth.php'; // Check if user is authenticated

// Include cfg.php for database configuration
include 'cfg.php';

// Get the username from the session variables
$username = $_SESSION['user'];

// Fetch best_time from the database using the connection defined in cfg.php
$query = "SELECT best_time FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($best_time);
$stmt->fetch();
$stmt->close();

// Include accountDelete.php file
include 'accountDelete.php';

?>

<!-- HTML content starts here -->

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8"> <!-- Set character encoding for the document -->
    <title>Account</title> <!-- Set page title -->
    <link rel="stylesheet" href="styles/styles.css"> <!-- Include stylesheet for overall styling -->
    <link rel="stylesheet" href="styles/navstyle.css"> <!-- Include stylesheet for navigation bar -->
    <link rel="stylesheet" href="styles/account.css"> <!-- Include stylesheet specific to this account page -->
</head>
<body>
    <?php include 'nav.php'; ?> <!-- Include navigation bar -->
    
    <main class="account">
        <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2> <!-- Display welcome message with username -->
        <p>This is your account page.</p> <!-- Brief description of the page -->

        <div class="account-info">
            <p>Your best time: <?php echo htmlspecialchars($best_time ?? 'N/A'); ?></p> <!-- Display best_time or "N/A" if not set -->
        </div>

        <div class="bottom-actions">
            <a href="logout.php" class="logout-link">Logout</a> <!-- Link to logout page -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <button type="submit" name="delete_account">Delete Account</button> <!-- Form for deleting the account -->
            </form>
        </div>
    </main>
</body>
</html>
