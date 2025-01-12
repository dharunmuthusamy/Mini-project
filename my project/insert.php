<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = ""; // Add your database password if applicable
$dbname = "hospital_db";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $contact = $_POST['contact'];

    // Prepare an SQL statement to insert the data
    $sql = "INSERT INTO patients (name, age, gender, contact) VALUES ('$name', $age, '$gender', '$contact')";

    if ($conn->query($sql) === TRUE) {
        echo "New patient added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
