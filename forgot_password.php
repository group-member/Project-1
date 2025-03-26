<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $email = $_POST['email'];

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'specific');
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Check if the email exists in the users table
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // Email exists, generate reset token
        $token = bin2hex(random_bytes(50)); // Generate a random token

        // Set expiration time to 1 hour from now
        $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Insert token into password_resets table
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expires_at);
        $stmt->execute();

        // Send the reset link to the user’s email
        $reset_link = "http://localhost/your_project/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Click the link to reset your password: $reset_link";

        // Send email
        if (mail($email, $subject, $message)) {
            $success_message = 'A password reset link has been sent to your email.';
        } else {
            $error_message = 'Failed to send email. Please try again.';
        }
    } else {
        $error_message = 'No user found with this email.';
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
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style_validation.css">
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>

        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if (isset($success_message)): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="email">Enter your email:</label>
            <input type="email" id="email" name="email" required><br><br>
            <input type="submit" value="Reset Password">
        </form>
        <p>Remembered your password? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
