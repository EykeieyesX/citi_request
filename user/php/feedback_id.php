<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../../index.html");
    exit();
}

// Include the database connection
include '../config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the request number from URL
$request_number = isset($_GET['request_number']) ? $_GET['request_number'] : '';

// Verify the request exists and belongs to the user
$username = $_SESSION['username'];
$sql = "SELECT * FROM barangay_id WHERE request_number = ? AND username = ? AND status = 'completed'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $request_number, $username);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();

if (!$request) {
    echo "Request not found, not completed, or you don't have permission to provide feedback!";
    exit();
}

// Check if feedback already exists for this request
$feedback_exists = false;
$existing_feedback = null;
$feedback_check_sql = "SELECT * FROM feedback_id WHERE request_number = ? AND username = ?";
$feedback_check_stmt = $conn->prepare($feedback_check_sql);
$feedback_check_stmt->bind_param("ss", $request_number, $username);
$feedback_check_stmt->execute();
$feedback_result = $feedback_check_stmt->get_result();

if ($feedback_result->num_rows > 0) {
    $feedback_exists = true;
    $existing_feedback = $feedback_result->fetch_assoc();
}

// Handle form submission only if no feedback exists
$submitted_feedback = null;
if (!$feedback_exists && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $accessibility = isset($_POST['accessibility']) ? (int)$_POST['accessibility'] : 0;
    $timeliness = isset($_POST['timeliness']) ? (int)$_POST['timeliness'] : 0;
    $user_experience = isset($_POST['user_experience']) ? (int)$_POST['user_experience'] : 0;
    $complaint = isset($_POST['complaint']) ? $conn->real_escape_string($_POST['complaint']) : '';

    // Insert feedback into database
    $insert_sql = "INSERT INTO feedback_id (request_number, username, accessibility, timeliness, user_experience, complaint, feedback_date) 
                   VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ssiiis", $request_number, $username, $accessibility, $timeliness, $user_experience, $complaint);
    
    if ($insert_stmt->execute()) {
        $feedback_success = "Thank you for your feedback!";
        // Store the submitted values to display
        $submitted_feedback = [
            'accessibility' => $accessibility,
            'timeliness' => $timeliness,
            'user_experience' => $user_experience,
            'complaint' => $complaint
        ];
        $feedback_exists = true; // Now feedback exists
    } else {
        $feedback_error = "Error submitting feedback. Please try again.";
    }
    
    $insert_stmt->close();
}

// Check if we should show the feedback view
$show_feedback_view = $feedback_exists || isset($feedback_success);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provide Feedback - Barangay ID Request</title>
    <link rel="stylesheet" href="../../style.css">
    <link rel="icon" type="image/x-icon" href="../../images/lguicon.png" />
    <style>
        .feedback-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .feedback-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .rating-section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: white;
            border-radius: 5px;
        }
        
        .rating-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .star-rating {
            display: flex;
            justify-content: space-between;
            max-width: 300px;
            margin: 0 auto;
            flex-direction: row-reverse;
        }
        
        .star-rating input[type="radio"] {
            display: none;
        }
        
        .star-rating label {
            font-size: 24px;
            color: #ccc;
            cursor: pointer;
            order: 1;
        }
        
        .star-rating input[type="radio"]:checked ~ label,
        .star-rating input[type="radio"]:checked + label {
            color: #ffcc00;
        }
        
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #ffcc00;
        }
        
        .star-display {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        
        .star-display .star {
            font-size: 24px;
            color: #ffcc00;
            margin: 0 2px;
        }
        
        .complaint-section {
            margin-top: 30px;
        }
        
        .complaint-section textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            min-height: 100px;
            resize: vertical;
        }
        
        .complaint-display {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin-top: 15px;
        }
        
        .form-buttons {
            margin-top: 30px;
            text-align: center;
        }
        
        .form-buttons button, .form-buttons a {
            padding: 10px 20px;
            margin: 0 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px;
        }
        
        .submit-btn {
            background-color: #4CAF50;
            color: white;
        }
        
        .back-btn {
            background-color: #6c757d;
            color: white;
        }
        
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .feedback-view {
            display: <?php echo $show_feedback_view ? 'block' : 'none'; ?>;
        }
        
        .feedback-form {
            display: <?php echo $show_feedback_view ? 'none' : 'block'; ?>;
        }
        
        .rating-value {
            text-align: center;
            margin-top: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="feedback-container">
    <div class="feedback-header">
        <h2>Feedback for Request #<?php echo htmlspecialchars($request_number); ?></h2>
        <p style="color: #000000;">
            <?php echo $feedback_exists ? 'Thank you for your feedback!' : 'Hello, thank you for following up with a feedback. Please rate your experience.'; ?>
        </p>
    </div>
    
    <?php if (isset($feedback_success)): ?>
        <div class="message success">
            <?php echo $feedback_success; ?>
        </div>
    <?php elseif (isset($feedback_error)): ?>
        <div class="message error">
            <?php echo $feedback_error; ?>
        </div>
    <?php endif; ?>
    
    <!-- Feedback Form (hidden if feedback exists) -->
    <div class="feedback-form" style="display: <?php echo $feedback_exists ? 'none' : 'block'; ?>">
        <form method="POST" action="feedback_id.php?request_number=<?php echo htmlspecialchars($request_number); ?>">
            <div class="rating-section">
                <div class="rating-title">Accessibility (How easy was it to request and receive your ID?)</div>
                <div class="star-rating">
                    <input type="radio" id="accessibility5" name="accessibility" value="5" required>
                    <label for="accessibility5">★</label>
                    <input type="radio" id="accessibility4" name="accessibility" value="4">
                    <label for="accessibility4">★</label>
                    <input type="radio" id="accessibility3" name="accessibility" value="3">
                    <label for="accessibility3">★</label>
                    <input type="radio" id="accessibility2" name="accessibility" value="2">
                    <label for="accessibility2">★</label>
                    <input type="radio" id="accessibility1" name="accessibility" value="1">
                    <label for="accessibility1">★</label>
                </div>
            </div>
            
            <div class="rating-section">
                <div class="rating-title">Timeliness (How satisfied are you with the processing time?)</div>
                <div class="star-rating">
                    <input type="radio" id="timeliness5" name="timeliness" value="5" required>
                    <label for="timeliness5">★</label>
                    <input type="radio" id="timeliness4" name="timeliness" value="4">
                    <label for="timeliness4">★</label>
                    <input type="radio" id="timeliness3" name="timeliness" value="3">
                    <label for="timeliness3">★</label>
                    <input type="radio" id="timeliness2" name="timeliness" value="2">
                    <label for="timeliness2">★</label>
                    <input type="radio" id="timeliness1" name="timeliness" value="1">
                    <label for="timeliness1">★</label>
                </div>
            </div>
            
            <div class="rating-section">
                <div class="rating-title">User Experience (How was your overall experience with the process?)</div>
                <div class="star-rating">
                    <input type="radio" id="user_experience5" name="user_experience" value="5" required>
                    <label for="user_experience5">★</label>
                    <input type="radio" id="user_experience4" name="user_experience" value="4">
                    <label for="user_experience4">★</label>
                    <input type="radio" id="user_experience3" name="user_experience" value="3">
                    <label for="user_experience3">★</label>
                    <input type="radio" id="user_experience2" name="user_experience" value="2">
                    <label for="user_experience2">★</label>
                    <input type="radio" id="user_experience1" name="user_experience" value="1">
                    <label for="user_experience1">★</label>
                </div>
            </div>
            
            <div class="complaint-section">
                <div class="rating-title">Complaint (Optional - if you have any complaints or suggestions)</div>
                <textarea name="complaint" placeholder="Please describe any issues or provide suggestions for improvement..."></textarea>
            </div>
            
            <div class="form-buttons">
                <a href="../track.php" class="back-btn">Go back to Track</a>
                <button type="submit" class="submit-btn">Submit Feedback</button>
            </div>
        </form>
    </div>
    
    <!-- Feedback View (shown if feedback exists) -->
    <?php if ($show_feedback_view): ?>
    <div class="feedback-view">
        <?php 
        // Use existing feedback if available, otherwise use newly submitted feedback
        $display_feedback = $existing_feedback ?? $submitted_feedback;
        ?>
        
        <div class="rating-section">
            <div class="rating-title">Your Accessibility Rating</div>
            <div class="star-display">
                <?php 
                $accessibility = $display_feedback['accessibility'] ?? 0;
                for ($i = 0; $i < $accessibility; $i++): ?>
                    <span class="star">★</span>
                <?php endfor; ?>
                <?php for ($i = $accessibility; $i < 5; $i++): ?>
                    <span class="star">☆</span>
                <?php endfor; ?>
            </div>
            <div class="rating-value"><?php echo $accessibility; ?> out of 5 stars</div>
        </div>
        
        <div class="rating-section">
            <div class="rating-title">Your Timeliness Rating</div>
            <div class="star-display">
                <?php 
                $timeliness = $display_feedback['timeliness'] ?? 0;
                for ($i = 0; $i < $timeliness; $i++): ?>
                    <span class="star">★</span>
                <?php endfor; ?>
                <?php for ($i = $timeliness; $i < 5; $i++): ?>
                    <span class="star">☆</span>
                <?php endfor; ?>
            </div>
            <div class="rating-value"><?php echo $timeliness; ?> out of 5 stars</div>
        </div>
        
        <div class="rating-section">
            <div class="rating-title">Your User Experience Rating</div>
            <div class="star-display">
                <?php 
                $user_experience = $display_feedback['user_experience'] ?? 0;
                for ($i = 0; $i < $user_experience; $i++): ?>
                    <span class="star">★</span>
                <?php endfor; ?>
                <?php for ($i = $user_experience; $i < 5; $i++): ?>
                    <span class="star">☆</span>
                <?php endfor; ?>
            </div>
            <div class="rating-value"><?php echo $user_experience; ?> out of 5 stars</div>
        </div>
        
        <?php if (!empty($display_feedback['complaint'])): ?>
        <div class="rating-section">
            <div class="rating-title">Your Complaint/Suggestion</div>
            <div class="complaint-display">
                <?php echo htmlspecialchars($display_feedback['complaint']); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="form-buttons">
            <a href="../track.php" class="back-btn">Go back to Track</a>
        </div>
    </div>
    <?php endif; ?>
</div>
</body>
</html>

<?php
$stmt->close();
$feedback_check_stmt->close();
$conn->close();
?>