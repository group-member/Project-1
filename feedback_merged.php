<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "feedback_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $comments = $conn->real_escape_string($_POST['comments']);

    // Ensure 'uploads/' directory exists
    if (!file_exists('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Handle file upload
    $target_file = "";
    if (!empty($_FILES["file"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["file"]["name"]);
        if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $target_file = ""; // Reset if upload fails
        }
    }

    // Insert feedback into database
    $stmt = $conn->prepare("INSERT INTO feedback (name, email, rating, comments, file) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiss", $name, $email, $rating, $comments, $target_file);
    
    if ($stmt->execute()) {
        echo "<script>alert('Thank you for your feedback!'); window.location.href='';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch feedback based on the offset
$feedbackOffset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$sql = "SELECT * FROM feedback ORDER BY submission_time DESC LIMIT 5 OFFSET $feedbackOffset";
$result = $conn->query($sql);
$feedbackData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $feedbackData[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Feedback</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        
        /* General Page Styles */
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; line-height: 1.6; }
        .container { max-width: 700px; margin: auto; padding: 20px; }
        
        /* Form Styles */
        form { max-width: 500px; margin-bottom: 20px; background-color: #fff; padding: 25px; border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        input, textarea { width: 100%; padding: 12px; margin-top: 8px; margin-bottom: 20px; border: 1px solid #ccc; 
            border-radius: 5px; font-size: 1rem; box-sizing: border-box; transition: border-color 0.3s ease; }
        input:focus, textarea:focus { border-color: #4CAF50; outline: none; }
        textarea { height: 100px; resize: vertical; }
        
        /* Rating Styles */
        .star-rating { font-size: 30px; display: flex; align-items: center; gap: 10px; margin-bottom: 15px; cursor: pointer; }
        .star-rating i { color: #ccc; transition: color 0.3s, transform 0.2s ease-in-out; }
        .star-rating i.active, .star-rating i:hover, .star-rating i:hover ~ i { color: #f39c12; }
        .star-rating i.selected { color: #f39c12; }
        
        /* Rating Description */
        .rating-description { font-size: 1rem; margin-top: 5px; color: #555; font-weight: bold; }
        
        /* Feedback Items */
        .feedback-item { border: 1px solid #ddd; padding: 20px; margin-bottom: 12px; background: #fff; border-radius: 10px; 
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1); transition: transform 0.2s ease-in-out; }
        .feedback-item:hover { transform: scale(1.02); }
        
        /* Button */
        button { width: 100%; padding: 14px; font-size: 1rem; background-color: #4CAF50; color: white; border: none; 
            border-radius: 5px; cursor: pointer; transition: background 0.3s ease-in-out; }
        button:hover { background-color: #45a049; }
        
        /* Thank You Message */
        #thank-you-message { text-align: center; font-size: 1.5rem; color: #4CAF50; font-weight: bold; }
        
        /* Responsive */
        @media (max-width: 600px) { form { width: 90%; padding: 18px; } button { font-size: 0.9rem; padding: 12px; } }
    </style>
</head>
<body>
   <!-- Button to go back to the Dashboard -->
   <button onclick="window.location.href='dashboard.html'">Go back to Dashboard</button>
<div class="container">
    <h2>Submit Your Feedback</h2>

    <!-- Feedback Form -->
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label>Rating:</label>
        <div class="star-rating" id="rating-stars">
            <i class="fas fa-star" data-value="1"></i>
            <i class="fas fa-star" data-value="2"></i>
            <i class="fas fa-star" data-value="3"></i>
            <i class="fas fa-star" data-value="4"></i>
            <i class="fas fa-star" data-value="5"></i>
        </div>
        <p class="rating-description" id="rating-description">Hover over stars to see rating meaning</p>
        <input type="hidden" name="rating" id="rating-value" required>

        <label for="comments">Comments (optional):</label>
        <textarea id="comments" name="comments"></textarea>

        <label for="file">Upload Image (optional):</label>
        <input type="file" id="file" name="file" accept="image/*">
        
        <button type="submit">Submit Feedback</button>
    </form>

    <!-- Display Success Message -->
    <div id="thank-you-message" style="display: none;">
        <h3>Thank you for your feedback!</h3>
    </div>

    <!-- Recent Feedback -->
    <h2>Latest Feedback</h2>
    <div id="feedback-container">
        <?php foreach ($feedbackData as $row): ?>
            <div class="feedback-item">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <div class="star-rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star <?= $i <= $row['rating'] ? 'active' : '' ?>"></i>
                    <?php endfor; ?>
                </div>
                <p><?= nl2br(htmlspecialchars($row['comments'])) ?></p>
                <?php if ($row['file']): ?>
                    <img src="<?= htmlspecialchars($row['file']) ?>" alt="Uploaded Image" style="max-width: 100%; height: auto;">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

     <button id="load-more" onclick="loadMoreFeedback()">View More Feedback</button>
</div>

<script>
// Load more feedback via AJAX
let feedbackOffset = <?php echo $feedbackOffset; ?>;
function loadMoreFeedback() {
    feedbackOffset += 5;
    window.location.href = 'feedback_merged.php?offset=' + feedbackOffset;
}

// Star rating logic
const stars = document.querySelectorAll('#rating-stars i');
const ratingValue = document.getElementById('rating-value');
const ratingDescription = document.getElementById('rating-description');
let currentRating = 0;

stars.forEach(star => {
    star.addEventListener('mouseover', () => {
        let value = parseInt(star.getAttribute('data-value'));
        setStarRating(value);
    });
    star.addEventListener('click', () => {
        currentRating = parseInt(star.getAttribute('data-value'));
        ratingValue.value = currentRating;
    });
});

function setStarRating(value) {
    stars.forEach(star => {
        if (parseInt(star.getAttribute('data-value')) <= value) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });

    switch (value) {
        case 1:
            ratingDescription.textContent = 'Poor: Needs a lot of improvement';
            break;
        case 2:
            ratingDescription.textContent = 'Fair: Some issues need fixing';
            break;
        case 3:
            ratingDescription.textContent = 'Average: Works fine but could be better';
            break;
        case 4:
            ratingDescription.textContent = 'Very Good: Almost perfect"';
            break;
        case 5:
            ratingDescription.textContent = 'Excellent: Absolutely amazing!';
            break;
    }
}
</script>

</body>
</html>

