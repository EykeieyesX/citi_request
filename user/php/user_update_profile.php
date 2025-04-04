<?php
session_start();

// Include the database configuration file
require_once '../config.php';

<<<<<<< HEAD
if (!isset($_SESSION['username'])) {
    header("Location: ../../index.html");
=======
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user data from the database
$currentUsername = $_SESSION['username'];
$stmt = $conn->prepare("SELECT username, firstname, lastname, email, password FROM usercredentials WHERE username = ?");
$stmt->bind_param("s", $currentUsername);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists and fetch data
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $storedUsername = $row['username'];
    $storedFirstname = $row['firstname'];
    $storedLastname = $row['lastname'];
    $storedEmail = $row['email'];
    $storedPassword = $row['password']; // Password hash
} else {
    header("Location: ../User.php?error=User not found");
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
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

<<<<<<< HEAD
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
=======
    // Verify current password
    if (!password_verify($currentPassword, $storedPassword)) {
        header("Location: ../User.php?error=Incorrect current password");
        exit();
    }

    // Update profile data
    $stmt = $conn->prepare("UPDATE usercredentials SET username = ?, firstname = ?, lastname = ?, email = ? WHERE username = ?");
    $stmt->bind_param("sssss", $newUsername, $newFirstname, $newLastname, $newEmail, $currentUsername);

    if ($stmt->execute()) {
        // Update password if provided
        if (!empty($newPassword)) {
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE usercredentials SET password = ? WHERE username = ?");
            $stmt->bind_param("ss", $hashedNewPassword, $newUsername);
            $stmt->execute();
        }
        header("Location: ../User.php?success=profile_updated");
    } else {
        header("Location: ../User.php?error=" . urlencode($stmt->error));
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
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
