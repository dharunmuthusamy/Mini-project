<?php
$host = 'localhost';
$db = 'blood_bank';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function sanitizeInput($data) {
    $data = trim($data);
    $data = strip_tags($data);
    return $data;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $name = sanitizeInput($_POST['name']);
    $age = filter_var($_POST['age'], FILTER_VALIDATE_INT);
    $weight = filter_var($_POST['weight'], FILTER_VALIDATE_INT);
    $blood_type = sanitizeInput($_POST['bloodType']);
    $health = sanitizeInput($_POST['health']);
    $medical_history = sanitizeInput($_POST['medicalHistory']);
    $transfusion = sanitizeInput($_POST['transfusion']);
    $medications = sanitizeInput($_POST['medications']);
    $travel = sanitizeInput($_POST['travel']);
    $risk_behavior = sanitizeInput($_POST['riskBehavior']);
    $pregnancy = sanitizeInput($_POST['pregnancy']);
    $phone_number = sanitizeInput($_POST['phone_number']);
    if ($age >= 18 && $age <= 60 && $weight >= 50 && 
        $health === 'yes' && $medical_history === 'no' && 
        $transfusion === 'no' && $medications === 'no' && 
        $travel === 'no' && $risk_behavior === 'no' && 
        $pregnancy === 'no') {
        $stmt = $conn->prepare("INSERT INTO donars (name, age, weight, blood_type, health, medical_history, transfusion, medications, travel, risk_behavior, pregnancy, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siisssssssss", $name, $age, $weight, $blood_type, $health, $medical_history, $transfusion, $medications, $travel, $risk_behavior, $pregnancy, $phone_number);
        if ($stmt->execute()) {
           header("Location:home.html");
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "You do not meet the criteria to donate blood.";
    }
} else {
    echo "Form not submitted.";
}
$conn->close();
?>
