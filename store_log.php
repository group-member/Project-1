<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "specific";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect POST data
$user_id = $_POST['user_id'];
$timestamp = $_POST['timestamp'];
$status = $_POST['status'];
$prediction = $_POST['prediction'];

// Log the received POST data
error_log("Received data: user_id=$user_id, timestamp=$timestamp, status=$status, prediction=$prediction");

// Check if the user_id exists in the users table
$check_user_sql = "SELECT id FROM users WHERE id = ?";
$stmt_check = $conn->prepare($check_user_sql);
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows == 0) {
    die("Error: The user ID does not exist.");
}

$stmt_check->close();

// Prepare SQL query to insert data into user_data table
$sql = "INSERT INTO user_data (user_id, timestamp, status, prediction) VALUES (?, ?, ?, ?)";

// Prepare and bind the statement
if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("isss", $user_id, $timestamp, $status, $prediction);

    // Execute the query
    if ($stmt->execute()) {
        echo "Log stored successfully";
    } else {
        echo "Error storing log: " . $stmt->error;  // Display detailed error if any
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Error preparing statement: " . $conn->error;  // Display error preparing statement
}

// Close the connection
$conn->close();
?>
