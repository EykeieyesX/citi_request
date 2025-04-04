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
    $middleName = $_POST['middleName'];
    $middleInitial = $_POST['middleInitial'];
    $dob = $_POST['dob'];
    $maritalStatus = $_POST['maritalStatus'];
    $genderType = $_POST['genderType'];
    $location = $_POST['location'];

    // Generate request number
    $query = "SELECT COUNT(*) AS total FROM barangay_id";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $requestNumber = str_pad($row['total'] + 1, 5, '0', STR_PAD_LEFT);

    $residentNumber = "RES-" . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
    $precinctNumber = "4-" . rand(1000, 9999);
    $validUntil = date('Y-m-d', strtotime('+5 years'));

    // Default values
    $status = "Submitted";  // Default status
    $paid = "No";  // Default payment status

    // Insert data into the database
    $sql = "INSERT INTO barangay_id 
        (request_number, username, lastname, firstname, middlename, middleinitial, dateofbirth, civilstatus, gender, residentnumber, precinctnumber, location, validuntil, status, paid) 
        VALUES 
        ('$requestNumber', '$username', '$lastName', '$firstName', '$middleName', '$middleInitial', '$dob', '$maritalStatus', '$genderType', '$residentNumber', '$precinctNumber', '$location', '$validUntil', '$status', '$paid')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration Successful! Your Request Number is: $requestNumber');</script>";
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../images/lguicon.png"/>
    <title>Barangay Resident Registration</title>
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
        #map {
            height: 300px;
            width: 100%;
            margin-top: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="registration-wrapper">
        <h1 class="registration-title">Barangay Resident Registration</h1>
        <form id="residentRegistrationForm" action="payment.php" method="POST">
            <label class="form-label" for="lastName">Last Name</label>
            <input class="form-input" type="text" id="lastName" name="lastName" required>

            <label class="form-label" for="firstName">First Name</label>
            <input class="form-input" type="text" id="firstName" name="firstName" required>

            <label class="form-label" for="middleName">Middle Name</label>
            <input class="form-input" type="text" id="middleName" name="middleName">

            <label class="form-label" for="middleInitial">Middle Initial</label>
            <input class="form-input" type="text" id="middleInitial" name="middleInitial" maxlength="1">

            <label class="form-label" for="dob">Date of Birth</label>
            <input class="form-input" type="date" id="dob" name="dob" required>

            <label class="form-label" for="maritalStatus">Civil Status</label>
            <select class="form-select" id="maritalStatus" name="maritalStatus" required>
                <option value="">Select</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Widowed">Widowed</option>
                <option value="Divorced">Divorced</option>
            </select>

            <label class="form-label" for="genderType">Gender</label>
            <select class="form-select" id="genderType" name="genderType" required>
                <option value="">Select</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <label class="form-label" for="location">Address</label>
            <input class="form-input" type="text" id="location" name="location" required>
            <div id="map"></div> 
            <input type="hidden" name="request_type" value="id">

            <button class="submit-btn" type="submit" name="register">Register</button>
            
        </form>
    </div>

    <script src="maps.js"></script> 
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
</body>
</html>
