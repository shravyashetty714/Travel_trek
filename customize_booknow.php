<?php
// customize_booknow.php

// 1. Database connection settings
$servername = "localhost";
$username   = "root";      // Default XAMPP user
$password   = "";          // Default XAMPP password
$dbname     = "traveltrek"; // Use your existing database

// Create connection using PDO
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// 2. Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // sanitize inputs
    $name         = htmlspecialchars($_POST['name']);
    $destination  = htmlspecialchars($_POST['destination']);
    $address      = htmlspecialchars($_POST['address']);
    $travel_date  = $_POST['travel_date'];
    $travel_time  = $_POST['travel_time'];
    $notes        = htmlspecialchars($_POST['notes']);

    // insert into custom_bookings table
    $stmt = $conn->prepare(
        "INSERT INTO custom_bookings
         (name, destination, address, travel_date, travel_time, notes)
         VALUES
         (:name, :destination, :address, :travel_date, :travel_time, :notes)"
    );
    $stmt->execute([
        ':name'        => $name,
        ':destination' => $destination,
        ':address'     => $address,
        ':travel_date' => $travel_date,
        ':travel_time' => $travel_time,
        ':notes'       => $notes
    ]);

    $message = "✅ Your custom travel plan has been saved!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customize & Book Now | TravelTrek</title>
    <style>
        /* Custom Color Palette */
        :root {
            --primary-color: #6a7c7a;
            --secondary-color: rgb(6, 97, 66);
            --tertiary-color: rgb(11, 96, 92);
            --accent-color: #0A675F;
            --highlight-color: rgb(169, 240, 233);
            --background-color: rgb(47, 96, 9);
            --white-color: #fff;
            --light-green: #90EE90;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: var(--white-color); /* White text color */
        }

        /* Container for the form */
        .container {
            background-color: var(--secondary-color);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            margin: 50px auto;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 600;
            color: var(--light-green); /* Light green for header */
            text-align: center;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.2); /* Optional shadow */
            border-bottom: 3px solid var(--highlight-color); /* Bottom border with highlight color */
            padding-bottom: 10px;
        }

        label {
            font-size: 1.1rem;
            font-weight: 500;
        }

        input[type="text"], input[type="date"], input[type="time"], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 30px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        textarea {
            resize: vertical;
            height: 100px;
        }

        button {
            background-color: var(--tertiary-color);
            border: none;
            font-weight: 600;
            border-radius: 30px;
            padding: 12px;
            font-size: 1.2rem;
            width: 100%;
            cursor: pointer;
        }

        button:hover {
            background-color: var(--highlight-color);
        }

        .message {
            margin-top: 20px;
            padding: 10px;
            background: #e6f5ea;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            color: #155724;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Customize Your Booking</h1>

        <?php if (!empty($message)): ?>
            <div class="message"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST" action="customize_booknow.php">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required>

            <label for="destination">Destination</label>
            <input type="text" id="destination" name="destination" required>

            <label for="address">Destination Address</label>
            <input type="text" id="address" name="address" required>

            <label for="travel_date">Travel Date</label>
            <input type="date" id="travel_date" name="travel_date" required>

            <label for="travel_time">Travel Time</label>
            <input type="time" id="travel_time" name="travel_time" required>

            <label for="notes">Additional Notes</label>
            <textarea id="notes" name="notes" rows="3"></textarea>

            <button type="submit">Save & Book</button>
        </form>
    </div>

</body>
</html>
