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
    $lastName = $data['lastName'];
    $firstName = $data['firstName'];
    $middleName = $data['middleName'];
    $middleInitial = $data['middleInitial'];
    $dob = $data['dob'];
    $maritalStatus = $data['maritalStatus'];
    $genderType = $data['genderType'];
    $location = $data['location'];

    // Get transaction number
    $transactionId = trim($_POST['transaction_id']);
    if (empty($transactionId)) {
        echo "<script>alert('Error: Transaction Number is required.'); window.history.back();</script>";
        exit();
    }

    // Generate request number
    $query = "SELECT COUNT(*) AS total FROM barangay_id";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $requestNumber = str_pad($row['total'] + 1, 5, '0', STR_PAD_LEFT);

    $residentNumber = "RES-" . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
    $precinctNumber = "4-" . rand(1000, 9999);
    $validUntil = date('Y-m-d', strtotime('+5 years'));
    $dateSubmitted = date('Y-m-d H:i:s'); // Current date and time

    // Insert data into database, including transaction ID, username, and datesubmitted
    $sql = "INSERT INTO barangay_id (request_number, username, lastname, firstname, middlename, middleinitial, dateofbirth, civilstatus, gender, residentnumber, precinctnumber, location, validuntil, transaction_id, datesubmitted) 
            VALUES ('$requestNumber', '$username', '$lastName', '$firstName', '$middleName', '$middleInitial', '$dob', '$maritalStatus', '$genderType', '$residentNumber', '$precinctNumber', '$location', '$validUntil', '$transactionId', '$dateSubmitted')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration Successful! Your Request Number is: $requestNumber'); window.location.href = 'Request.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    // Clear session data
    unset($_SESSION['registration_data']);
    $conn->close();
}
?>