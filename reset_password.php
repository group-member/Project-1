<?php
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'specific');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Check if the token exists and is valid
    $stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($email, $expires_at);
    $stmt->fetch();

    // Check if the token has expired
    if ($stmt->num_rows > 0 && strtotime($expires_at) > time()) {
        // Token is valid, show reset form
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Collect new password and sanitize inputs
            $password = trim($_POST['password']);
            $repeat_password = trim($_POST['repeat_password']);

            if ($password !== $repeat_password) {
                echo '<p class="error">Passwords do not match. Please try again.</p>';
            } else {
                // Hash the new password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Update the user's password in the database
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                $stmt->bind_param("ss", $hashed_password, $email);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    // Delete the reset token (to invalidate it)
                    $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
                    $stmt->bind_param("s", $token);
                    $stmt->execute();

                    echo '<p>Your password has been successfully reset. <a href="login.php">Login</a></p>';
                } else {
                    echo '<p class="error">There was an error resetting your password. Please try again later.</p>';
                }
            }
        }
    } else {
        echo '<p class="error">The password reset link is invalid or has expired.</p>';
    }

    $stmt->close();
    $conn->close();
} else {
    echo '<p class="error">No reset token provided.</p>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="style_validation.css">
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="POST">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <label for="repeat_password">Confirm Password:</label>
            <input type="password" id="repeat_password" name="repeat_password" required><br><br>

            <input type="submit" value="Reset Password">
        </form>
    </div>
</body>
</html>
