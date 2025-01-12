<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $bookerName = trim($_POST['booker_name']);
    $bookerPhone = trim($_POST['booker_phone']);
    $donarId = $_POST['donar_id'] ?? null;
    $bloodType = $_POST['blood_type'] ?? null;

    // Check if the necessary data is present
    if (!$donarId || !$bloodType) {
        // Redirect if missing data
        header("Location: blood.php");
        exit;
    }

    // Here you would typically process the booking (e.g., save to the database)
    // Example: Save booking to a database
    // Assuming you have a database connection set up
    $host = 'localhost'; 
    $db = 'blood_bank'; 
    $user = 'root'; 
    $pass = ''; 

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the booking into a bookings table (create the table as needed)
    $stmt = $conn->prepare("INSERT INTO bookings (donar_id, booker_name, booker_phone, name,blood_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $donarId, $bookerName, $bookerPhone,  $donarName,$bloodType);
    
    if ($stmt->execute()) {
        // Success: You could set a session message or redirect to a confirmation page
        echo "Booking confirmed! Thank you for your donation.";
    } else {
        // Handle error
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();

    // Clear session data after processing
    session_unset();
    session_destroy();
} else {
    // Redirect if accessed without POST
    header("http://localhost/my project/dddw.php");
    exit;
}
?>
