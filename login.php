<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the 'specific' database
    $conn = new mysqli('localhost', 'root', '', 'specific');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Prepare and execute query to fetch user data
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);

    if ($stmt->fetch()) {
        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start session and redirect to the dashboard
            $_SESSION['user_id'] = $id;
            header('Location: dashboard.html');
            exit;
        } else {
            // Invalid password
            $error_message = 'Invalid username or password.';
        }
    } else {
        // Username not found
        $error_message = 'Invalid username or password.';
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style_validation.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>

        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br><br>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <input type="submit" value="Login">
        </form>
        <p>Forgot your password? <a href="forgot_password.php">Click here</a></p>
        <p>Don't have an account? <a href="sign_up.php">Sign Up</a></p>
    </div>
</body>
</html>
