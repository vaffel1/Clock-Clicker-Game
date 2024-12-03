<?php
/**
 * batch_account_creation.php: A script to generate fake accounts and insert them into a users table.
 */

// Constants for the number of users to generate
const amountOfUsers = 10;

// Require the configuration file
require 'cfg.php';

/**
 * Generate a random username with the specified length.
 *
 * @param int $length The length of the username (default: 8)
 * @return string A random username
 */
function generateRandomUsername($length = 8) {
    // Define the characters that can be used in the username
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
    // Generate a random string of the specified length
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        // Randomly select a character from the defined set and append it to the string
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $randomString;
}

/**
 * Generate a random password with the specified length.
 *
 * @param int $length The length of the password (default: 8)
 * @return string A random password
 */
function generateRandomPassword($length = 8) {
    // Define the characters that can be used in the password
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
    
    // Generate a random string of the specified length
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        // Randomly select a character from the defined set and append it to the string
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    
    return $randomString;
}

// Loop through the specified number of users to generate
for ($i = 0; $i < amountOfUsers; $i++) {
    // Generate a random username and password
    $rndUsername = generateRandomUsername();
    $rndPassword = generateRandomPassword();

    // Hash the generated password using bcrypt
    $hashed_password = password_hash($rndPassword, PASSWORD_BCRYPT);

    // Generate a random best time between 300 and 2400 seconds
    $best_time = rand(300, 2400);

    try {
        // Prepare an SQL statement to insert into the users table
        $stmtUsers = $conn->prepare("INSERT INTO users (username, password_hash, best_time) VALUES (?, ?, ?)");
        
        // Check if the statement was prepared successfully
        if ($stmtUsers === false) {
            throw new Exception("Failed to prepare statement for users: " . $conn->error);
        }
        
        // Bind the variables to be inserted into the statement
        $stmtUsers->bind_param("sii", $rndUsername, $hashed_password, $best_time);

        // Execute the statement
        if (!$stmtUsers->execute()) {
            throw new Exception("Failed to execute statement for users: " . $stmtLeaderboard->error);
        }

        // Check if the insert was successful
        if ($stmtUsers->affected_rows > 0) {
            echo "New record created for username: " . $rndUsername . "\n";
        } else {
            echo "An unexpected error occurred. Please try again later.\n";
        }
    } catch (Exception $e) {
        // Handle duplicate entries
        if ($conn->errno == 1062) { // MySQL error code for duplicate entry
            echo "Username already exists: " . $rndUsername . "\n";
        } else {
            error_log("Registration failed: " . $e->getMessage());
            echo "An unexpected error occurred. Please try again later.\n";
        }
    }

    // Close the statement if it was successfully prepared
    if (isset($stmtUsers)) {
        $stmtUsers->close();
    }

    // Prepare an SQL statement to insert into the leaderboard table
    try {
        $stmtLeaderboard = $conn->prepare("INSERT INTO leaderboard (username, best_time) VALUES (?, ?)");
        
        // Check if the statement was prepared successfully
        if ($stmtLeaderboard === false) {
            throw new Exception("Failed to prepare statement for leaderboard: " . $conn->error);
        }
        
        // Bind the variables to be inserted into the statement
        $stmtLeaderboard->bind_param("si", $rndUsername, $best_time);

        // Execute the statement
        if (!$stmtLeaderboard->execute()) {
            throw new Exception("Failed to execute statement for leaderboard: " . $stmtLeaderboard->error);
        }

        echo "New user inserted into leaderboard successfully.\n";
    } catch (Exception $e) {
        error_log("Leaderboard insertion failed: " . $e->getMessage());
        echo "An unexpected error occurred while inserting into the leaderboard. Please try again later.\n";
    }

    // Close the statement if it was successfully prepared
    if (isset($stmtLeaderboard)) {
        $stmtLeaderboard->close();
    }
}

// Close the database connection
$conn->close();
?>
