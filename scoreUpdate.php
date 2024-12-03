<?php
/**
 * scoreUpdate.php - Function to update a user's best time in the database.
 *
 * This script checks if the user is logged in, updates their best time with the provided value,
 * and stores the result in the database. If any errors occur during this process, they are caught
 * and handled accordingly.
 */

// Start the session, which allows PHP to store and manage data between HTTP requests.
session_start();

// Include the configuration file for establishing a connection to the database.
include 'cfg.php';

// Check if the user is logged in by checking for an active session.
if (!isset($_SESSION['user'])) {
    // If the user is not logged in, display an error message and exit the script.
    die("User not logged in.");
}

// Ensure that the username and best time values are stored as strings and integers respectively,
// to prevent potential security vulnerabilities (SQL injection).
$username = (string)$_SESSION['user'];  // Ensure it's a string
$bestTime = (int)$_POST['best_time'];  // Ensure it's an integer

echo "Updating user: $username with best time: $bestTime"; 
// Debugging output to verify the values being updated.

// Check if the database connection is established successfully.
if ($conn->connect_error) {
    // If an error occurs, display an error message and exit the script.
    die("Connection failed: " . $conn->connect_error);
}

try {
    // Prepare a secure query to update the user's best time in the database.
    $stmt = $conn->prepare("UPDATE users SET best_time = ? WHERE username = ?");
    
    // Bind parameters to prevent SQL injection.
    $stmt->bind_param("is", $bestTime, $username);

    // Debugging output to verify the values being updated.
    echo "Updating user: $username with best time: $bestTime"; 
    
    if ($stmt->execute()) {
        // If the update is successful, display a success message.
        echo "Best time updated successfully.";
    } else {
        // If an error occurs during the update process, display an error message.
        echo "Error updating best time: " . $conn->error;
    }

    // Close the statement to free up resources and improve performance.
    $stmt->close();
} catch (Exception $e) {
    // Handle any errors that occur during the execution of the script.
    echo "Error: " . $e->getMessage();
}

// Close the database connection to release system resources.
$conn->close();
?>
