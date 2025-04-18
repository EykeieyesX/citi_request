<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the default time zone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Include the database configuration file
require_once '../config.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set MySQL time zone to Asia/Manila (UTC+8)
$conn->query("SET time_zone = '+08:00'");

// Get form data
$email = $_POST['email'];
$topic = $_POST['topic'];
$description = $_POST['description'];
$location = $_POST['location'];
$images = isset($_FILES['images']) ? $_FILES['images'] : null; // Check if images exist

// Check if the topic is "Feedback"
if ($topic === 'Feedback') {
    // Generate a unique FeedbackID
    $feedback_id = strtoupper(substr(bin2hex(random_bytes(4)), 0, 7)); // No "FB-" prefix here

    // Prepare and bind for feedback table
    $stmt = $conn->prepare("INSERT INTO feedback (FeedbackID, Email, Topic, Description, Location, Images, Submitted_date) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssss", $feedback_id, $email, $topic, $description, $location, $images['name']); 

    // Handle file upload
    if ($images && !empty($images['name'])) {
        if ($images['error'] === UPLOAD_ERR_OK) {
            move_uploaded_file($images['tmp_name'], "../../uploads/" . basename($images['name']));
        } else {
            echo "File upload error: " . $images['error'];
            exit; 
        }
    }

    // Execute the insert query
    if ($stmt->execute()) {
        echo "FB-" . $feedback_id; // Add the "FB-" prefix only here
    } else {
        echo "Error inserting feedback: " . $stmt->error; 
    }

    $stmt->close();
} else {
    // If it's not feedback, continue with the request table (unchanged)
    $reference_id = 'REF-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 7));
    $status = 'Submitted';

    // Prepare and bind for request table
    $stmt = $conn->prepare("INSERT INTO request (Email, Topic, Description, Location, Images, reference_id, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $email, $topic, $description, $location, $images['name'], $reference_id, $status); // Bind parameters

    // Handle file upload
    if ($images && !empty($images['name'])) {
        if ($images['error'] === UPLOAD_ERR_OK) {
            move_uploaded_file($images['tmp_name'], "../../uploads/" . basename($images['name']));
        } else {
            echo "File upload error: " . $images['error'];
            exit; 
        }
    }

    // Execute the insert query
    if ($stmt->execute()) {
        echo $reference_id; // Return the reference ID for other requests
    } else {
        echo "Error inserting request: " . $stmt->error; 
    }

    $stmt->close();
}

$conn->close();
?>
