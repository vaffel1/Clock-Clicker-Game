<?php
// updateLeaderboard.php

// Start a new session to access session variables
session_start();

// Include the database configuration file
include "cfg.php";

// Check if the best time was provided in the AJAX request
if (!isset($_POST['bestTime'])) {
    // If not, output an error message and exit
    echo "Error: Best time not provided.";
    exit;
}

// Check if the user is logged in by checking for a session variable
if (!isset($_SESSION['user'])) {
    // If not, output an error message and exit
    echo "Error: User not logged in.";
    exit;
}

// Get the best time from the AJAX request and the username from the session
$bestTime = $_POST['bestTime'];
$username = $_SESSION['user'];

// Prepare a query to check if the user exists in the leaderboard table
$stmtCheckUser = $conn->prepare("SELECT 1 FROM leaderboard WHERE username = ?");
$stmtCheckUser->bind_param("s", $username);
$stmtCheckUser->execute();
$stmtCheckUser->store_result();

// Check if the user exists by checking the number of rows returned by the query
if ($stmtCheckUser->num_rows === 0) {
    // If not, insert a new row into the leaderboard table with the user's best time
    $stmtInsertUser = $conn->prepare("INSERT INTO leaderboard (username, best_time) VALUES (?, ?)");
    $stmtInsertUser->bind_param("si", $username, $bestTime);
    
    // Check if the insertion was successful and output a success message or error message as needed
    if ($stmtInsertUser->execute()) {
        echo "New user inserted successfully.";
    } else {
        echo "Error inserting new user: " . $conn->error;
        exit;
    }
    $stmtInsertUser->close();
}

// Prepare an update statement to update the best time for the existing user
$stmtUpdate = $conn->prepare("UPDATE leaderboard SET best_time = ? WHERE username = ?");
$stmtUpdate->bind_param("is", $bestTime, $username);

// Check if the update was successful and output a success message or error message as needed
if ($stmtUpdate->execute()) {
    echo "Best time updated successfully";
} else {
    echo "Error updating best time: " . $conn->error;
}

// Close any open statements to free up resources
$stmtCheckUser->close();
$stmtUpdate->close();
$conn->close();
?>
