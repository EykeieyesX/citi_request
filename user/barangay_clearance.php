<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the user is logged in and has a username
    if (!isset($_SESSION['username'])) {
        echo "<script>alert('You must be logged in to register.');</script>";
        exit();
    }

    // Retrieve form data
    $username = $_SESSION['username'];  // Get logged-in user's username
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $middleInitial = $_POST['middleInitial'];
    $age = $_POST['age'];

    // Generate request number using barangay_id table
    $query = "SELECT COUNT(*) AS total FROM barangay_id";
    $result = $conn->query($query);
    
    // Check if query executed successfully
    if ($result) {
        $row = $result->fetch_assoc();
        // If no rows exist in the table, we start from 1
        $crequestNumber = str_pad($row['total'] + 1, 5, '0', STR_PAD_LEFT);
    } else {
        echo "<script>alert('Error with query execution: " . $conn->error . "');</script>";
        exit();
    }

    // Default values
    $status = "Submitted";  // Default status
    $paid = "Yes";  // Default payment status
    $dateIssued = null;  // Default date issued (null for now)

    // Insert data into the database
    $sql = "INSERT INTO barangay_clearance 
        (crequest_number, username, lastname, firstname, middleinitial, age, status, dateissued, paid) 
        VALUES 
        ('$crequestNumber', '$username', '$lastName', '$firstName', '$middleInitial', '$age', '$status', '$dateIssued', '$paid')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration Successful! Your Request Number is: $crequestNumber');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../images/lguicon.png"/>
    <title>Barangay Clearance Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eaeaea;
        }
        .registration-wrapper {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
        }
        .registration-title {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 20px;
        }
        .form-label {
            font-weight: bold;
            display: block;
            margin-top: 8px;
            font-size: 14px;
        }
        .form-input, .form-select {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .submit-btn {
            width: 100%;
            padding: 10px;
            background-color: #5a55ff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 15px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #4740e0;
        }
    </style>
</head>
<body>
    <div class="registration-wrapper">
        <h1 class="registration-title">Barangay Clearance Registration</h1>
        <form id="clearanceRegistrationForm" action="clearancepayment.php" method="POST">
            <label class="form-label" for="firstName">First Name</label>
            <input class="form-input" type="text" id="firstName" name="firstName" required>

            <label class="form-label" for="middleInitial">Middle Initial</label>
            <input class="form-input" type="text" id="middleInitial" name="middleInitial" maxlength="1" required>

            <label class="form-label" for="lastName">Last Name</label>
            <input class="form-input" type="text" id="lastName" name="lastName" required>

            <label class="form-label" for="age">Age</label>
            <input class="form-input" type="number" id="age" name="age" required>

            <button class="submit-btn" type="submit" name="register">Register</button>
        </form>
    </div>
</body>
</html>
