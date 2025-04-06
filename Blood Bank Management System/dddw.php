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
$sql = "SELECT blood_type, donar_count FROM blood_type_counts";
$result = $conn->query($sql);
$conn->query("CALL UpdateBloodTypeCounts()");


$bloodTypeCounts = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $bloodTypeCounts[] = $row;
    }
} else {
    die("Query failed: " . $conn->error);
}
$donars = [];
$selectedBloodType = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['blood_type'])) {
    $selectedBloodType = $_POST['blood_type'];
    $sql = "SELECT id, name, age, weight, phone_number FROM donars WHERE blood_type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedBloodType);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $donars[] = $row;
        }
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Blood Types - Blood Bank Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            position: relative; /* Position context for video */
            overflow: auto; /* Prevent scrolling */
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
        header {
            background: rgba(192, 57, 43, 0.8);
            color: white;
            padding: 20px;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in;
            position: relative; /* Ensure header stays above video */
        }
        h1 {
            margin: 0;
            font-size: 2.5rem;
        }
        section {
            margin: 20px;
            width: 90%;
            max-width: 800px;
            position: relative; /* Ensure section stays above video */
        }
        h2 {
            color: #c0392b;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .blood-type-button, .book-button {
            width: 300px; /* Reduced width */
            background-color: #c0392b;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 10px; /* Reduced padding */
            margin: 5px 0;
            font-size: 1rem; /* Reduced font size */
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Slightly smaller shadow */
        }
        .blood-type-button:hover, .book-button:hover {
            background-color: #a93226;
            transform: scale(1.05);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            opacity: 0;
            transform: translateY(20px);
            animation: slideIn 0.5s forwards; /* Animation effect */
        }
        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            transition: background-color 0.3s;
        }
        th {
            background-color: #c0392b;
            color: white;
        }
        td:hover {
            background-color: #f2f2f2;
        }
        section a {
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
    </style>
</head>
<body>
    <video autoplay muted loop>
        <source src="ddedbg.mp4" type="video/mp4">
    </video>
    <header>
        <h1>Blood Bank Management System</h1>
    </header>
    <section>
       <br>
       <br> <h2>Available Blood Types</h2>
        <?php if (!empty($bloodTypeCounts)): ?>
            <?php foreach ($bloodTypeCounts as $bloodType): ?>
                <form action="" method="POST" style="display:inline;">
                    <input type="hidden" name="blood_type" value="<?php echo htmlspecialchars($bloodType['blood_type']); ?>">
                    <button type="submit" class="blood-type-button">
                        <?php echo htmlspecialchars($bloodType['blood_type']); ?> (<?php echo htmlspecialchars($bloodType['donar_count']); ?> units)
                    </button>
                </form>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No blood type data available.</p>
        <?php endif; ?>

        <?php if (!empty($donars)): ?>
            <h3>Donors for Blood Type: <?php echo htmlspecialchars($selectedBloodType); ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Weight</th>
                        <th>Phone Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($donars as $donar): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($donar['id']); ?></td>
                            <td><?php echo htmlspecialchars($donar['name']); ?></td>
                            <td><?php echo htmlspecialchars($donar['age']); ?></td>
                            <td><?php echo htmlspecialchars($donar['weight']); ?></td>
                            <td><?php echo htmlspecialchars($donar['phone_number']); ?></td>
                            <td>
                                <form action="booker_form.php" method="POST">
                                    <input type="hidden" name="donar_id" value="<?php echo htmlspecialchars($donar['id']); ?>">
                                    <input type="hidden" name="blood_type" value="<?php echo htmlspecialchars($selectedBloodType); ?>">
                                    <button type="submit" class="book-button">Book</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
    <section>
        <a href="home.html">Back to home</a>
    </section>
</body>
</html>
