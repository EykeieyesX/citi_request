<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once '../config.php';

// Get username and password from the POST request
$username = $_POST["username"];
$password = $_POST["password"];

// Query the database for the user with the provided username
$stmt = $conn->prepare("SELECT * FROM census_credentials WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Check if a user with that username exists
if ($result->num_rows > 0) {
    // Fetch user data
    $user = $result->fetch_assoc();

    // Verify the password against the stored hash
    if (password_verify($password, $user["password_hash"])) {
        // Successful login, set session variables
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["firstname"] = $user["firstname"];
        $_SESSION["middlename"] = $user["middlename"];
        $_SESSION["lastname"] = $user["lastname"];
        $_SESSION["areaofcencusstreet"] = $user["areaofcencusstreet"];
        $_SESSION["api_id"] = $user["api_id"];

        // Redirect to the home page or dashboard
        header("Location: ../Home.php");
        exit();
    } else {
        // Password is incorrect, redirect with an error message
        header("Location: ../../index.html?error=password");
        exit();
    }
} else {
    // User with the provided username was not found, redirect with an error message
    header("Location: ../../index.html?error=user_not_found");
    exit();
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
