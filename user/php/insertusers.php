<?php
require_once '../config.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verify database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$users = [
    ["Citizen00001", "John", "Doe", "Mitch", "johndoe@gmail.com", "CitizenpasswordDo", 23, "March 10, 2002", "male", "Bachelor's", "Single", "Php 60,000", "123 Main St, Quezon City"],
    ["Citizen00002", "Jane", "Smith", "Anne", "janesmith@gmail.com", "CitizenpasswordSm", 28, "April 15, 1997", "female", "Master's", "Single", "Php 85,000", "456 Elm St, Caloocan City"],
    ["Citizen00003", "Michael", "Brown", "James", "michaelbrown@gmail.com", "CitizenpasswordBr", 35, "July 22, 1990", "male", "PhD", "Married", "Php 120,000", "789 Oak Ave, Quezon City"],
    ["Citizen00004", "Emily", "Johnson", "Rose", "emilyjohnson@gmail.com", "CitizenpasswordJo", 30, "September 5, 1995", "female", "Bachelor's", "Single", "Php 75,000", "321 Pine Rd, Caloocan City"],
    ["Citizen00005", "David", "Williams", "Lee", "davidwilliams@gmail.com", "CitizenpasswordWi", 27, "November 12, 1998", "male", "Bachelor's", "Single", "Php 65,000", "654 Cedar Ln, Quezon City"],
    ["Citizen00006", "Sarah", "Miller", "Grace", "sarahmiller@gmail.com", "CitizenpasswordMi", 32, "February 28, 1993", "female", "Master's", "Married", "Php 95,000", "987 Maple Dr, Caloocan City"],
    ["Citizen00007", "James", "Davis", "Robert", "jamesdavis@gmail.com", "CitizenpasswordDa", 40, "January 18, 1985", "male", "Bachelor's", "Divorced", "Php 80,000", "135 Walnut St, Quezon City"],
    ["Citizen00008", "Jessica", "Martinez", "Marie", "jessicamartinez@gmail.com", "CitizenpasswordMa", 26, "August 7, 1999", "female", "Bachelor's", "Single", "Php 70,000", "246 Birch Blvd, Caloocan City"],
    ["Citizen00009", "Daniel", "Garcia", "Thomas", "danielgarcia@gmail.com", "CitizenpasswordGa", 29, "May 30, 1996", "male", "Bachelor's", "Single", "Php 68,000", "369 Spruce Ave, Quezon City"],
    ["Citizen00010", "Ashley", "Rodriguez", "Claire", "ashleyrodriguez@gmail.com", "CitizenpasswordRo", 31, "October 14, 1994", "female", "Master's", "Single", "Php 90,000", "482 Acacia Rd, Caloocan City"],
    ["Citizen00011", "Matthew", "Wilson", "Paul", "matthewwilson@gmail.com", "CitizenpasswordWi", 24, "December 3, 2001", "male", "Bachelor's", "Single", "Php 55,000", "591 Mahogany St, Quezon City"],
    ["Citizen00012", "Amanda", "Anderson", "Ruth", "amandaanderson@gmail.com", "CitizenpasswordAn", 33, "June 19, 1992", "female", "Bachelor's", "Married", "Php 78,000", "714 Narra Ln, Caloocan City"],
    ["Citizen00013", "Christopher", "Taylor", "Henry", "christophertaylor@gmail.com", "CitizenpasswordTa", 36, "April 2, 1989", "male", "Master's", "Married", "Php 110,000", "827 Molave Dr, Quezon City"],
    ["Citizen00014", "Stephanie", "Thomas", "Joy", "stephaniethomas@gmail.com", "CitizenpasswordTh", 25, "July 8, 2000", "female", "Bachelor's", "Single", "Php 62,000", "940 Kamagong Ave, Caloocan City"],
    ["Citizen00015", "Andrew", "Hernandez", "Mark", "andrewhernandez@gmail.com", "CitizenpasswordHe", 38, "September 21, 1987", "male", "PhD", "Married", "Php 130,000", "153 Ipil Rd, Quezon City"],
    ["Citizen00016", "Nicole", "Moore", "Beth", "nicolemoore@gmail.com", "CitizenpasswordMo", 29, "February 14, 1996", "female", "Bachelor's", "Single", "Php 72,000", "266 Yakal St, Caloocan City"],
    ["Citizen00017", "Joshua", "Martin", "Luke", "joshuamartin@gmail.com", "CitizenpasswordMa", 27, "November 27, 1998", "male", "Bachelor's", "Single", "Php 67,000", "379 Tanguile Ln, Quezon City"],
    ["Citizen00018", "Megan", "Jackson", "Ann", "meganjackson@gmail.com", "CitizenpasswordJa", 34, "January 5, 1991", "female", "Master's", "Married", "Php 88,000", "492 Lauan Dr, Caloocan City"],
    ["Citizen00019", "Ryan", "White", "Scott", "ryanwhite@gmail.com", "CitizenpasswordWh", 31, "May 17, 1994", "male", "Bachelor's", "Single", "Php 74,000", "505 Apitong Blvd, Quezon City"],
    ["Citizen00020", "Lauren", "Harris", "Kate", "laurenharris@gmail.com", "CitizenpasswordHa", 26, "August 30, 1999", "female", "Bachelor's", "Single", "Php 63,000", "618 Banaba Ave, Caloocan City"]
];

// Verify the table exists
$table_check = $conn->query("SHOW TABLES LIKE 'usercredentials'");
if ($table_check->num_rows == 0) {
    die("Error: The 'usercredentials' table doesn't exist in the database.");
}

// Prepare the insert statement
$sql = "INSERT INTO usercredentials 
        (username, firstname, lastname, middlename, email, password, age, dateofbirth, gender, educationlevel, maritalstatus, income, address, reset_token, token_expiry) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, NULL)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Track successful inserts
$success_count = 0;
$errors = [];

foreach ($users as $user) {
    $hashedPassword = password_hash($user[5], PASSWORD_DEFAULT);
    
    // Convert date to MySQL format (YYYY-MM-DD)
    $mysql_date = date('Y-m-d', strtotime($user[7]));
    
    if (!$stmt->bind_param("ssssssissssss", 
        $user[0], $user[1], $user[2], $user[3], $user[4],
        $hashedPassword, $user[6], $mysql_date, $user[8],
        $user[9], $user[10], $user[11], $user[12]
    )) {
        $errors[] = "Binding failed for {$user[0]}: " . $stmt->error;
        continue;
    }
    
    if (!$stmt->execute()) {
        $errors[] = "Execute failed for {$user[0]}: " . $stmt->error;
    } else {
        $success_count++;
    }
}

// Output results
echo "<h2>Insertion Results</h2>";
echo "<p>Successfully inserted: $success_count users</p>";

if (!empty($errors)) {
    echo "<h3>Errors encountered:</h3>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
}

// Verify the data was inserted
$verify = $conn->query("SELECT COUNT(*) as count FROM usercredentials");
if ($verify) {
    $row = $verify->fetch_assoc();
    echo "<p>Total users in database now: " . $row['count'] . "</p>";
} else {
    echo "<p>Could not verify inserted records: " . $conn->error . "</p>";
}

$stmt->close();
$conn->close();
?>