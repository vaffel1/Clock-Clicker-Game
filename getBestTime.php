<?php
/**
 * getBestTime.php - Function to retrieve the best time for a user.
 *
 * This script checks if a valid session exists and, if so, queries the database to retrieve the user's best time.
 */

// Start the session, which allows PHP to store and manage data between HTTP requests.
session_start();

// Include your database configuration file, which sets up the connection variables.
require 'cfg.php';

// Check if a valid user session exists.
if (isset($_SESSION['user'])) {
    // Get the username from the session array.
    $username = $_SESSION['user'];

    try {
        // Prepare an SQL statement to select the best time for the specified user.
        $stmt = $conn->prepare("
            SELECT best_time FROM users WHERE username = ?
        ");
        
        // Bind the username parameter to prevent SQL injection attacks.
        $stmt->bind_param("s", $username);
        
        // Execute the query and store the result in a variable.
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Fetch the first row from the result set (assuming there's only one user with that username).
        $row = $result->fetch_assoc();

        // Check if the row exists and if it contains a 'best_time' value.
        if ($row && isset($row['best_time'])) {
            // If both conditions are true, output the best time as JSON-encoded integer.
            echo json_encode((int)$row['best_time']);
        } else {
            // If either condition is false, output 0 (indicating no best time was found).
            echo json_encode(0);
        }
    } catch (Exception $e) {
        // Log any errors that occur during the query execution.
        error_log("Error fetching best time: " . $e->getMessage());
        
        // Handle errors by returning 0 (indicating failure).
        echo json_encode(0);
    }
} else {
    // If no valid user session exists, return an unauthorized response with a status code of 401.
    http_response_code(401);
    
    // Output 0 to indicate that the request was not successful.
    echo json_encode(0);
}
?>
