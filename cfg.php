<?php
// cfg.php

// Database configuration
$host = 'localhost'; // Change this if your database is hosted elsewhere
$dbname = 'clock_clicker'; // Replace with your database name
$username = 'BigBoss'; // Replace with your database username
$password = 'BossB1g'; // Replace with your database password

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>