<!-- leaderboard.php - Leaderboard page for displaying top scores -->

<!-- Add the close button to allow users to dismiss the leaderboard -->
<button class="close-button" onclick="closeLeaderboard()">
    <img src="assets/close.svg">
</button>

<?php
/**
 * Include the configuration file for database connection.
 *
 * This script establishes a connection to the database and retrieves the top 10 scores from the leaderboard table.
 */

// Include the configuration file that sets up the database connection variables.
include 'cfg.php';

// SQL query to fetch the top 10 scores ordered by best_time DESC (newest times first) and limit the result to the top 10
$sql = "SELECT id, username, best_time FROM leaderboard ORDER BY best_time DESC LIMIT 10";

// Execute the SQL query on the database connection object.
$result = $conn->query($sql);

// Start the HTML table that will display the leaderboard scores.
echo "<table border='1'>";
echo "<tr><th>Rank</th><th>Name</th><th>Best Time (s)</th></tr>";

// Variable to keep track of the current rank number, starting from 1.
$rank = 1;

// Loop through the result set and display each row in the table.
while($row = $result->fetch_assoc()) {
    // Check if we've reached the 10th score. If so, stop displaying scores.
    if ($rank > 10) {
        break;
    }

    // Display a table row for the current score.
    echo "<tr>";
    echo "<td>" . $rank . "</td>";
    echo "<td>" . $row["username"] . "</td>";
    echo "<td>" . $row["best_time"] . "</td>";
    echo "</tr>";

    // Increment the rank number for the next score.
    $rank++;
}

// Close the HTML table and database connection to free up resources.
echo "</table>";
$conn->close();
?>
