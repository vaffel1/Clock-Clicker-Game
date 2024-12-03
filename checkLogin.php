<?php
/**
 * checkLogin.php - Function to verify if a user's session is active.
 *
 * This script checks if a valid session exists by checking for the presence of the 'user' key in the session array.
 */

// Start the session, which allows PHP to store and manage data between HTTP requests.
session_start();

// Check if the 'user' key is set in the session array (i.e., if a user's session is active).
if (isset($_SESSION['user'])) {
    // If the 'user' key is present, output 'true', indicating that a valid session exists.
    echo 'true';
} else {
    // If the 'user' key is not present, output 'false', indicating that no valid session exists.
    echo 'false';
}
?>
