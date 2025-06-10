<?php
// bookpackage.php

// Connect to database
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";      // Default XAMPP password
$dbname = "traveltrek";  // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch available tour packages
$query = "SELECT * FROM tour_packages";
$result = $conn->query($query);

// Handle booking form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $package_id = $_POST['package_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $booking_date = date('Y-m-d'); // current date
    
    // Insert booking
    $sql = "INSERT INTO bookings (package_id, name, email, booking_date) 
            VALUES ('$package_id', '$name', '$email', '$booking_date')";
    
    if ($conn->query($sql) === TRUE) {
        $message = "🎉 Booking confirmed for <strong>$name</strong>!";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Package - TravelTrek</title>
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
            margin: 0;
            padding: 0;
        }

        /* Container Styling */
        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
            background-color: var(--secondary-color);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1, h2 {
            text-align: center;
            color: var(--light-green);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            border-bottom: 3px solid var(--highlight-color);
            padding-bottom: 10px;
        }

        .message {
            margin: 20px auto;
            padding: 15px;
            background-color: #d4edda;
            color:rgb(1, 11, 4);
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            text-align: center;
        }

        /* Packages Section */
        .packages {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .package-card {
            background-color: #e6f5ea;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            padding: 20px;
            width: 280px;
            text-align: center;
        }

        .package-card h3 {
            color: var(--primary-color);
        }

        .package-card p {
            font-size: 0.95rem;
            margin: 8px 0;
        }

        /* Booking Form */
        .booking-form input[type="text"],
        .booking-form input[type="email"] {
            width: 90%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .booking-form button {
            padding: 10px 20px;
            background-color: var(--tertiary-color);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .booking-form button:hover {
            background-color: var(--highlight-color);
        }

        /* Itinerary Section Styling */
        #itinerary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
            padding: 60px 0;
            color: white;
            text-align: center; /* Center the text in the section */
        }

        #itinerary h2 {
            font-size: 2.5rem;
            font-weight: 600;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        #itinerary p {
            font-size: 1.1rem;
            color: rgba(215, 245, 219, 0.9);
            margin-bottom: 30px;
        }

        /* Customize Button */
        .btn-customize {
            display: inline-block;
            padding: 12px 24px;
            font-size: 1.2rem;
            font-weight: 600;
            color: #000;                             /* White text */
            background-color: rgb(216, 241, 222);               /* Green background color */
            border-radius: 50px;                     /* Rounded button */
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-top: 20px;
            text-align: center;                      /* Ensure text is centered in button */
        }

        .btn-customize:hover {
            background-color: #218838;               /* Darker green for hover */
            transform: translateY(-2px);             /* Lift effect on hover */
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🌍 Book Your Travel Package</h1>

    <?php if (!empty($message)) { echo "<div class='message'>$message</div>"; } ?>

    <h2>Available Packages</h2>

    <div class="packages">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="package-card">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($row['price']); ?></p>
                <p><strong>Available Dates:</strong> <?php echo htmlspecialchars($row['available_dates']); ?></p>

                <form method="POST" action="bookpackage.php" class="booking-form">
                    <input type="hidden" name="package_id" value="<?php echo $row['id']; ?>">
                    <input type="text" name="name" placeholder="Your Name" required>
                    <input type="email" name="email" placeholder="Your Email" required>
                    <button type="submit">Book Now</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

    <section id="itinerary">
        <div class="container">
            <h2 class="text-center mb-4">Customise and Reserve Your Plan</h2>
            <p class="text-center mb-4">Easily plan and organize your entire trip with our interactive itinerary builder.</p>
            <div class="text-center">
                <a href="customize_booknow.php" class="btn-customize">Customize</a>
            </div>
        </div>
    </section>
</div>

</body>
</html>

<?php $conn->close(); ?>
