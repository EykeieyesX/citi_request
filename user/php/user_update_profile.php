<?php
session_start();

// Include the database configuration file
require_once '../config.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../../index.html");
    exit();
}

// Get the posted form data
$id = $_POST['id'];
$api_id = $_POST['api_id'];
$username = $_POST['username'];
$firstname = $_POST['firstname'];
$middlename = $_POST['middlename'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$areaofcencusstreet = $_POST['areaofcencusstreet'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];

// Fetch the current password hash from the database
$stmt = $conn->prepare("SELECT password_hash FROM census_credentials WHERE id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$password_hash = $row['password_hash'];

// Check if the current password matches the one in the database
if (password_verify($current_password, $password_hash)) {
    // Proceed with updating the profile
    if (!empty($new_password)) {
        $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE census_credentials SET firstname = ?, middlename = ?, lastname = ?, email = ?, areaofcencusstreet = ?, password_hash = ? WHERE id = ?");
        $stmt->bind_param("sssssss", $firstname, $middlename, $lastname, $email, $areaofcencusstreet, $new_password_hash, $id);
    } else {
        $stmt = $conn->prepare("UPDATE census_credentials SET firstname = ?, middlename = ?, lastname = ?, email = ?, areaofcencusstreet = ? WHERE id = ?");
        $stmt->bind_param("ssssss", $firstname, $middlename, $lastname, $email, $areaofcencusstreet, $id);
    }
    
    if ($stmt->execute()) {
        header("Location: ../User.php?success=1");
    } else {
        header("Location: ../User.php?error=Failed to update profile");
    }
} else {
    header("Location: ../User.php?error=Incorrect current password");
}

$stmt->close();
$conn->close();
?>
