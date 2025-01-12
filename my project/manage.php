<?php
// Database connection
$host = 'localhost'; // Your database host
$db = 'blood_bank'; // Your database name
$user = 'root'; // Your database username
$pass = ''; // Your database password

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch blood type counts
$sql = "SELECT blood_type, donor_count FROM blood_type_counts";
$result = $conn->query($sql);

$bloodTypeCounts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bloodTypeCounts[] = $row;
    }
    
    
}
echo"";

$conn->close();
?>
