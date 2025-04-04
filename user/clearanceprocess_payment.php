<?php
session_start();
include 'config.php';

// Check if user confirmed payment
if (isset($_POST['confirmPayment'])) {
    if (!isset($_SESSION['registration_data'])) {
        die("No registration data found.");
    }

    // Ensure the user is logged in
    if (!isset($_SESSION['username'])) {
        echo "<script>alert('Error: You must be logged in.'); window.location.href='../index.html';</script>";
        exit();
    }

    // Retrieve stored form data
    $data = $_SESSION['registration_data'];
    $username = $_SESSION['username'];
    $firstName = $data['firstName'];
    $middleInitial = $data['middleInitial'];
    $lastname = $data['lastName'];
    $age = $data['age'];

    // Get transaction number
    $transactionId = trim($_POST['transaction_id']);
    if (empty($transactionId)) {
        echo "<script>alert('Error: Transaction Number is required.'); window.history.back();</script>";
        exit();
    }

    // Generate request number
    $query = "SELECT COUNT(*) AS total FROM barangay_clearance";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $crequestNumber = str_pad($row['total'] + 1, 5, '0', STR_PAD_LEFT);

    $ordinalNumber = "ORD-" . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
    $dateSubmitted = date('Y-m-d H:i:s'); // Current date and time

    // Insert data into database, including transaction ID, username, and datesubmitted
    $sql = "INSERT INTO barangay_clearance (crequest_number, username, firstname, middleinitial, lastname, age, ordinalnumber, transaction_id, datesubmitted) 
            VALUES ('$crequestNumber', '$username', '$firstName', '$middleInitial', '$lastname', '$age', '$ordinalNumber', '$transactionId', '$dateSubmitted')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration Successful! Your Request Number is: $crequestNumber'); window.location.href = 'Request.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    // Clear session data
    unset($_SESSION['registration_data']);
    $conn->close();
}
?>