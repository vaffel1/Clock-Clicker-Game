<?php
/**
 * auth.php - Authentication file for ensuring user sessions are active.
 *
 * This script checks if a valid session exists. If not, it redirects the user to the login page.
 */

// Start the session, which allows PHP to store and manage data between HTTP requests.
session_start();

// Check if the 'user' key is set in the session array.
if (!isset($_SESSION['user'])) {
    // If the 'user' key is not set, redirect the user to the login page.
    header("Location: login.php");
    exit();
}
?>
