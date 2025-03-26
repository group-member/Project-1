<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $email = $_POST['email'];
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $password = $_POST['password'];
    $repeat_password = $_POST['repeat_password'];

    // Basic validation
    if ($password !== $repeat_password) {
        $error_message = 'Passwords do not match.';
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Connect to the database
        $conn = new mysqli('localhost', 'root', '', 'specific');
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        // Prepare and bind statement
        $stmt = $conn->prepare("INSERT INTO users (email, username, fullname, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $username, $fullname, $hashed_password);

        // Execute and check for success
        if ($stmt->execute()) {
            $success_message = 'Sign-up successful. <a href="login.php"> Go to Login</a>';
        } else {
            $error_message = 'Error: ' . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style_validation.css">
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>

        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" required><br><br>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>
            
            <label for="repeat_password">Confirm Password:</label>
            <input type="password" id="repeat_password" name="repeat_password" required><br><br>
            
            <input type="submit" value="Sign Up">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
