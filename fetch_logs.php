<?php
// Database connection
$host = 'localhost';
$username = 'root'; // Default XAMPP username
$password = ''; // Default XAMPP password
$dbname = 'specific'; // Updated database name

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in before fetching data
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

// Get user_id from session
$user_id = $_SESSION['user_id'];

// Check if the request is for only the latest 5 logs (from the dashboard)
$latest_only = isset($_GET['latest']) && $_GET['latest'] === 'true';

// SQL query to fetch logs for the logged-in user
$sql = "SELECT timestamp, status, prediction FROM user_data WHERE user_id = ? ORDER BY timestamp";

// Add the LIMIT clause if only the latest 5 logs are requested
if ($latest_only) {
    $sql .= " LIMIT 5";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch all rows and store them in an array
$logs = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
}

// Return the logs as JSON
header('Content-Type: application/json');
echo json_encode($logs);

$conn->close();
?>
