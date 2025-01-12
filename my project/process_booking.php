<?php
session_start(); 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookerName = trim($_POST['booker_name']);
    $bookerPhone = trim($_POST['booker_phone']);
    $donarId = $_POST['donar_id'] ?? null;
    $bloodType = $_POST['blood_type'] ?? null;
    if (!$donarId || !$bloodType) {
        header("Location: blood.php");
        exit;
    }
    $host = 'localhost'; 
    $db = 'blood_bank'; 
    $user = 'root'; 
    $pass = ''; 

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $stmt = $conn->prepare("INSERT INTO bookings (donar_id, booker_name, booker_phone, name,blood_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $donarId, $bookerName, $bookerPhone,  $donarName,$bloodType);
    if ($stmt->execute()) {
        echo "Booking confirmed! Thank you for your donation.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    session_unset();
    session_destroy();
} else {
    header("http://localhost/my project/dddw.php");
    exit;
}
?>
