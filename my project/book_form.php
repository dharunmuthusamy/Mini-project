<?php
session_start();
$host = 'localhost'; 
$db = 'blood_bank'; 
$user = 'root'; 
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookerName = $_POST['booker_name'];
    $bookerPhone = $_POST['booker_phone'];
    $donarId = $_POST['donar_id'];
    $donarName = $_SESSION['donar_name']; 
    $blood_type = $_SESSION['blood_type'];
    $checkSql = "SELECT COUNT(*) FROM bookings WHERE donar_id = ? AND name = ? AND blood_type= ? AND booker_name = ? AND booker_phone = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("issss", $donarId, $donarName, $bookerName,$blood_type, $bookerPhone);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        echo "This booking already exists.";
    } else {
        $sql = "INSERT INTO bookings (booker_name, booker_phone, donar_id, name, blood_type, booking_date) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiss", $bookerName, $bookerPhone, $donarId, $donarName, $blood_type);
    if ($stmt->execute()) {
            header("Location:home.html");
        } else {
            echo "Error: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

$conn->close();
?>