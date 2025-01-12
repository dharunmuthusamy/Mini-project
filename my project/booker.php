<?php
session_start(); // Start the session

// Database connection
$host = 'localhost'; 
$db = 'blood_bank'; 
$user = 'root'; 
$pass = ''; 

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve donor ID from POST
if (isset($_POST['donar_id'])) {
    $donarId = $_POST['donar_id'];

    // Fetch donor details
    $sql = "SELECT name, blood_type FROM donars WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $donarId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $_SESSION['donar_id'] = $donarId;
        $_SESSION['blood_type'] = $row['blood_type'];
        $_SESSION['donar_name'] = $row['name']; // Store donor name in session
    }
    $stmt->close();
}

$conn->close();

// Ensure the user has reached this page with valid session data
if (!isset($_SESSION['donar_id']) || !isset($_SESSION['blood_type']) || !isset($_SESSION['donar_name'])) {
    header("Location: blood.php"); // Redirect to the main page if session data is missing
    exit;
}
?>