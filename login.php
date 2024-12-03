<?php
/**
 * login.php - Login page for users to enter their credentials.
 *
 * This script validates user input, checks the password against the stored hash, and sets a session variable if successful.
 */

// Start the session, which allows PHP to store and manage data between HTTP requests.
session_start();

// Include the configuration file that sets up the database connection variables.
require 'cfg.php';

// Initialize an error message variable to display any errors that occur during login.
$errorMessage = '';

// Check if the form has been submitted (i.e., if the method is POST).
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the username and password from the form input fields.
    $username = $_POST['username'];
    $entered_password = $_POST['password'];

    // Validate user input: ensure both username and password are provided.
    if (empty($username) || empty($entered_password)) {
        $errorMessage = "Username and password are required.";
    } else {
        try {
            // Prepare an SQL statement to select the user's ID, username, and password hash from the database.
            $stmt = $conn->prepare("
                SELECT id, username, password_hash
                FROM users
                WHERE username = ?
            ");
            
            // Bind the username parameter to prevent SQL injection attacks.
            $stmt->bind_param("s", $username);
            
            // Execute the query and store the result in a variable.
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            // Check if the user exists and the entered password matches the stored hash.
            if ($user && password_verify($entered_password, $user['password_hash'])) {
                // Set a session variable to indicate the user is logged in.
                $_SESSION['user'] = $user['username'];
                
                // Fetch the best time for the current user from the database.
                $bestTimeStmt = $conn->prepare("SELECT best_time FROM users WHERE username = ?");
                $bestTimeStmt->bind_param("s", $username);
                $bestTimeStmt->execute();
                $bestTimeResult = $bestTimeStmt->get_result();
                $bestTimeRow = $bestTimeResult->fetch_assoc();
                
                // Store the best time in session storage using JavaScript.
                if ($bestTimeRow && isset($bestTimeRow['best_time'])) {
                    echo "<script>sessionStorage.setItem('bestTime', '" . htmlspecialchars(json_encode((int)$bestTimeRow['best_time']), ENT_QUOTES, 'UTF-8') . "');</script>";
                }
                
                // Redirect the user to the account page.
                header("Location: account.php");
                exit();
            } else {
                $errorMessage = "Invalid credentials.";
            }
        } catch (Exception $e) {
            // Log any other errors that occur during login.
            error_log("Login failed: " . $e->getMessage());
            $errorMessage = "An unexpected error occurred. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8"> <!-- Character encoding for the document -->
    <title>Login</title> <!-- Page title -->
    
    <!-- Include CSS files for styling the form and navigation bar -->
    <link rel="stylesheet" href="styles/formstyles.css"> 
    <link rel="stylesheet" href="styles/navstyle.css">
</head>
<body>
    <?php include 'nav.php'; ?> <!-- Include the navigation bar -->
    
    <div class="form-container"> <!-- Container for the login form -->
        <h2>Login</h2> <!-- Page title -->

        <?php
        // Check if a registration success message should be displayed.
        if (isset($_GET['registered']) && $_GET['registered'] === 'success') {
            echo '<div class="success-message">Registration successful! You can now log in.</div>';
        }
        ?>
        
        <!-- The login form -->
        <form action="login.php" method="POST">
            Username: <input type="text" name="username" required><br> <!-- Input field for username (required) -->
            Password: <input type="password" name="password" required><br> <!-- Input field for password (required) -->
            <button type="submit">Login</button>
        </form>

        <?php if ($errorMessage): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($errorMessage); ?> <!-- Display any error messages -->
            </div>
        <?php endif; ?>

        <p>Don't have an account? <a href="register.php">Register here</a>.</p> <!-- Link to register page -->
    </div>
</body>
</html>
