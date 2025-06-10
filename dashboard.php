<?php
// dashboard.php

session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

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

// Fetch the user's travel history from custom_bookings
$user_id = $_SESSION['user_id']; // Assuming you saved user_id in session during login
$sql = "SELECT * FROM history WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$history_result = $stmt->get_result();

// Handle the form submission for new travel plans
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize user inputs
    $destination = htmlspecialchars($_POST['destination']);
    $address     = htmlspecialchars($_POST['address']);
    $travel_date = $_POST['travel_date'];
    $travel_time = $_POST['travel_time'];
    $notes       = htmlspecialchars($_POST['notes']);

    // Insert new travel plan into custom_bookings
    $insert_sql = "INSERT INTO history (user_id, destination, address, travel_date, travel_time, notes) 
                   VALUES (?, ?, ?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("isssss", $user_id, $destination, $address, $travel_date, $travel_time, $notes);

    if ($insert_stmt->execute()) {
        $message = "✅ Your travel plan has been saved!";
        header("Location: dashboard.php"); // Redirect to the same page to see the updated list
        exit();
    } else {
        $message = "❌ Error: " . $insert_stmt->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TravelTrek</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* Custom Color Palette */
    :root {
        --primary-color: #6a7c7a;
        --secondary-color: rgb(17, 145, 102);
        --tertiary-color: rgb(11, 96, 92);
        --accent-color: #0A675F;
        --highlight-color: rgb(169, 240, 233);
        --background-color: rgb(47, 96, 9);
        --white-color: #fff; /* White for text */
        --light-green:rgb(2, 78, 2); /* Light green for Welcome text */
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        color: var(--white-color); /* Set body text color to white */
    }

    /* Navbar */
    .navbar {
        background-color: var(--primary-color);
    }

    .navbar-brand, .nav-link {
        color: var(--white-color) !important; /* Navbar text color to white */
    }

    .navbar-nav .nav-link:hover {
        color: var(--highlight-color) !important;
    }

    /* Dashboard Section */
    .container {
        background-color: var(--secondary-color);
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 900px;
        margin-top: 50px;
    }
    .container h2 {
    font-size: 2.5rem;
    font-weight: 600;
    color: var(--light-green); /* Welcome text in light green */
    text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.2); /* Adding shadow */
    border-bottom: 3px solid var(--highlight-color); /* Adding a bottom border with highlight color */
    padding-bottom: 10px; /* Optional padding for space below the text */
}


    .form-label {
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--white-color); /* Form labels text to white */
    }

    .form-control {
        border-radius: 30px;
        font-size: 1rem;
        padding: 10px 20px;
        color: var(--white-color); /* Form control text to white */
        background-color: #333; /* Slightly dark background for input fields */
    }

    .btn-primary {
        background-color: var(--tertiary-color);
        border: none;
        font-weight: 600;
        border-radius: 30px;
        padding: 12px;
        font-size: 1.2rem;
    }

    .btn-primary:hover {
        background-color: var(--highlight-color);
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(232, 25, 25, 0.05);
    }

    .text-center a {
        color: var(--accent-color);
        font-weight: 600;
    }

    .text-center a:hover {
        color: var(--highlight-color);
        text-decoration: none;
    }

    /* Form Container */
    .form-container {
        background-color: rgb(223, 246, 236);
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
    }

    /* Footer */
    footer {
        background-color: var(--secondary-color);
        color: rgb(211, 241, 224);
        padding: 20px 0;
    }

    footer p {
        font-size: 1rem;
        color: var(--white-color); /* Footer text to white */
    }
</style>

<body>

<div class="container mt-5">
    <h2 class="text-center">Welcome, <?= $_SESSION['username'] ?>!</h2>

    <!-- Display message -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-info text-center">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <!-- Form for adding new travel plans -->
    <h3 class="mt-4">Add New Travel Plan</h3>
    <form action="dashboard.php" method="POST">
        <div class="mb-3">
            <label for="destination" class="form-label">Destination</label>
            <input type="text" class="form-control" id="destination" name="destination" required>
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>
        <div class="mb-3">
            <label for="travel_date" class="form-label">Travel Date</label>
            <input type="date" class="form-control" id="travel_date" name="travel_date" required>
        </div>
        <div class="mb-3">
            <label for="travel_time" class="form-label">Travel Time</label>
            <input type="time" class="form-control" id="travel_time" name="travel_time" required>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-100">Add Travel Plan</button>
    </form>

    <!-- Display user's travel history -->
    <h3 class="mt-5">Your Travel History</h3>
    <?php if ($history_result->num_rows > 0): ?>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Destination</th>
                    <th>Address</th>
                    <th>Travel Date</th>
                    <th>Travel Time</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $history_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['destination'] ?></td>
                        <td><?= $row['address'] ?></td>
                        <td><?= $row['travel_date'] ?></td>
                        <td><?= $row['travel_time'] ?></td>
                        <td><?= $row['notes'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center">You haven't added any travel plans yet.</p>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
