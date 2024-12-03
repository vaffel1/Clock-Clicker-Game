<?php
// register.php

require 'cfg.php';

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate input (you should add more robust validation in a real application)
    if (empty($username) || empty($password)) {
        $errorMessage = "Username and password are required.";
    } else {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $conn->prepare("
                INSERT INTO users (username, password_hash)
                VALUES (?, ?)
            ");
            
            $stmt->bind_param("ss", $username, $password_hash);
            $stmt->execute();

            // Check if the insert was successful
            if ($stmt->affected_rows > 0) {
                // Redirect to login.php with a success message
                header("Location: login.php?registered=success");
                exit();
            } else {
                $errorMessage = "An unexpected error occurred. Please try again later.";
            }
        } catch (Exception $e) {
            // Handle duplicate entries
            if ($conn->errno == 1062) { // MySQL error code for duplicate entry
                $errorMessage = "Username already exists.";
            }
            // Log other errors and provide a generic message
            error_log("Registration failed: " . $e->getMessage());
            $errorMessage = "An unexpected error occurred. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles/formstyles.css">
    <link rel="stylesheet" href="styles/navstyle.css">
</head>
<body>
    <?php include 'nav.php'; ?>
<div class="form-container">
    <h2>Register</h2>
    <form action="register.php" method="POST">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <button type="submit">Register</button>
    </form>

    <?php if ($errorMessage): ?>
        <div class="error-message">
            <?php echo htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
<?php
