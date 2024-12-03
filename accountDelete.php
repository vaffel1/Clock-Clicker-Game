<?php
/**
 * accountDelete.php - Handles user account deletion, including deleting their leaderboard score and account data.
 */

// Check if the request method is POST (i.e., a form has been submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /**
     * Delete the user's account and best score from the leaderboard table.
     */
    // SQL queries to delete data from users and leaderboard tables
    $sqlDeleteLeaderboard = "DELETE FROM leaderboard WHERE username = ?"; // Delete leaderboard entry for the specified username
    $sqlDeleteUser = "DELETE FROM users WHERE username = ?"; // Delete user account data

    /**
     * Prepare the SQL statements for execution.
     */
    // Create prepared statements for SQL queries
    $stmt1 = $conn->prepare($sqlDeleteUser); // Prepare statement to delete user account data
    $stmt2 = $conn->prepare($sqlDeleteLeaderboard); // Prepare statement to delete leaderboard entry

    /**
     * Check if the prepared statements were successful.
     */
    if ($stmt1 && $stmt2) {
        /**
         * Bind parameters to the prepared statements.
         */
        // Set parameter bindings for username
        $stmt1->bind_param("s", $username);
        $stmt2->bind_param("s", $username);

        /**
         * Execute the SQL queries.
         */
        // Execute statement to delete user account data
        $stmt1->execute();
        // Execute statement to delete leaderboard entry
        $stmt2->execute();

        /**
         * Close the prepared statements and connection.
         */
        // Close prepared statement for deleting user account data
        $stmt1->close();
        // Close prepared statement for deleting leaderboard entry
        $stmt2->close();
        // Close database connection
        $conn->close();

        echo "Account deleted successfully."; // Display success message

        /**
         * Redirect to logout.php after account deletion.
         */
        // Start or resume the session
        session_start(); // Start or resume the session
        // Unset all session variables
        session_unset(); // Unset all session variables
        // Destroy the session
        session_destroy(); // Destroy the session

        // Redirect to login page
        header("Location: login.php");
        exit();
    } else {
        echo "Error preparing statement: " . $conn->error; // Display error message if prepared statements were not successful
    }
}
?>
