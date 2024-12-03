<?php
/**
 * logout.php - Function to log out a user and destroy their session.
 */

// Start the session, which allows PHP to store and manage data between HTTP requests.
session_start();

// If the 'totalTime' key is set in the session array (i.e., if a user has logged in), reset its value to 0.
if (isset($_SESSION['totalTime'])) {
    $_SESSION['totalTime'] = 0;
}

// Unset all session variables, which removes them from memory.
session_unset();

// Destroy the current session.
session_destroy();

// Redirect the user back to the login page.
header("Location: login.php");

// Exit the script to prevent further execution.
exit();
?>
