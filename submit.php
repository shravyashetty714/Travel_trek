<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['name'];
    $destination = $_POST['destination'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $notes = $_POST['notes'];

    // 1. Connect to database
    $servername = "localhost";
    $username = "root"; // Default XAMPP username
    $password = "";     // Default XAMPP password is empty
    $dbname = "traveltrek"; // 👉 (Your database name)

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 2. Insert the form data into the database
    $sql = "INSERT INTO records (name, destination, start_date, end_date, notes) 
            VALUES ('$name', '$destination', '$start_date', '$end_date', '$notes')";

    if ($conn->query($sql) === TRUE) {
        // 3. Redirect to success page
        header("Location: success.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // 4. Close connection
    $conn->close();
} else {
    echo "Form not submitted properly.";
}
?>
