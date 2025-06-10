<?php
// login.php

session_start(); // Start session to store user information

// Database connection settings
$servername = "localhost";
$username   = "root";      // Default XAMPP user
$password   = "";          // Default XAMPP password
$dbname     = "traveltrek"; // Use your existing database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
$message = '';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize user inputs
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Check if the username exists in the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Successful login, create session
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");  // Redirect to dashboard (or home page)
            exit();
        } else {
            // Invalid password
            $message = "Invalid username or password!";
        }
    } else {
        // User doesn't exist
        $message = "Invalid username or password!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TravelTrek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Login Form -->
<div class="container">
    <h2 class="text-center my-4">Login</h2>

    <!-- Display error message if login fails -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-danger text-center">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <p class="text-center mt-3">Don't have an account? <a href="register.html">Register here</a></p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
