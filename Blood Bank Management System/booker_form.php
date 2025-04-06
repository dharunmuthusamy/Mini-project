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
if (isset($_POST['donar_id'])) {
    $donarId = $_POST['donar_id'];
    $sql = "SELECT name, blood_type FROM donars WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $donarId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $_SESSION['donar_id'] = $donarId;
        $_SESSION['blood_type'] = $row['blood_type'];
        $_SESSION['donar_name'] = $row['name'];
    }
    $stmt->close();
}

$conn->close();
if (!isset($_SESSION['donar_id']) || !isset($_SESSION['blood_type']) || !isset($_SESSION['donar_name'])) {
    header("Location: blood.php"); 
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Your Donation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8d7da;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h1 {
            color: #c0392b;
            margin-bottom: 20px;
        }
        section {
            margin: 20px;
            width: 90%;
            max-width: 800px;
            position: relative; /* Ensure section stays above video */
            text-align:center;
        } section a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #2c3e50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            
        }
        section a:hover {
            background-color: #141617;
            transform: translateY(-3px);
        }
        video {
            position: fixed; /* Fixed to stay in the background */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* Send video behind content */
            object-fit: cover; /* Cover the entire background */
        }
        .confirmation-box {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }
        .confirmation-box p {
            margin: 10px 0;
        }
        label {
            display: block;
            margin-top: 10px;
            text-align: left;
        }
        input[type="text"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            margin-top: 15px;
            background-color: #c0392b;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #a93226;
        }
        .success-message {
            display: none;
            margin-top: 20px;
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            animation: fadeIn 1s forwards;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
<video autoplay muted loop>
        <source src="final.mp4" type="video/mp4">
    </video>
    <h1>Confirm Your Donation</h1>
    <div class="confirmation-box">
        <p><strong>Donor ID:</strong> <?php echo htmlspecialchars($_SESSION['donar_id']); ?></p>
        <p><strong>Donor Name:</strong> <?php echo htmlspecialchars($_SESSION['donar_name']); ?></p>
        <p><strong>Blood Type:</strong> <?php echo htmlspecialchars($_SESSION['blood_type']); ?></p>

        <form id="booking-form" action="book_form.php" method="POST" onsubmit="showSuccessMessage(event)">
            <label for="booker_name">Your Name:</label>
            <input type="text" name="booker_name" required>

            <label for="booker_phone">Your Phone:</label>
            <input type="tel" name="booker_phone" required>

            <input type="hidden" name="donar_id" value="<?php echo htmlspecialchars($_SESSION['donar_id']); ?>">
            <input type="hidden" name="blood_type" value="<?php echo htmlspecialchars($_SESSION['blood_type']); ?>">
            
            <button type="submit">Confirm Booking</button>
        </form>
        <div id="success-message" class="success-message">Booking Successful!</div>
    </div>

    <script>
        function showSuccessMessage(event) {
            event.preventDefault(); 
            document.getElementById('success-message').style.display = 'block';
            setTimeout(() => {
                document.getElementById('booking-form').submit();
            }, 2000); 
        }
    </script>
     <section>
        <a href="home.html">Back to home</a>
    </section>
</body>
</html>
