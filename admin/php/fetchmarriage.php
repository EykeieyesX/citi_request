<?php

$servername = "localhost";
$username = "citi_lgutestdb1";
$password = "GGpfr89ly9h6qJF7";
$dbname = "citi_lgutestdb";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// API URL
$apiUrl = "https://civilregistrar.lgu2.com/api/marriage.php"; // API for marriage records

// Fetch data from the API
$response = file_get_contents($apiUrl);

if ($response === FALSE) {
    die("Error fetching data from API.");
}

// Decode JSON response
$data = json_decode($response, true);

if (!$data) {
    die("Error decoding JSON.");
}

// Prepare SQL query to check if ID exists
$checkStmt = $conn->prepare("SELECT id FROM marriage_records WHERE id = ?");

// Prepare SQL query to insert new records
$insertStmt = $conn->prepare("
    INSERT INTO marriage_records 
    (id, groom_first_name, groom_middle_name, groom_last_name, groom_suffix, bride_first_name, bride_middle_name, bride_last_name, bride_suffix, marriage_date, marriage_place, groom_witness, bride_witness, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Check if statements were prepared successfully
if (!$checkStmt || !$insertStmt) {
    die("Error preparing statements: " . $conn->error);
}

// Loop through data and insert only new records
foreach ($data as $item) {
    $id = $item['id'];

    // Check if the ID already exists
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows == 0) {
        // ID does not exist, insert new record
        $insertStmt->bind_param("isssssssssssss", 
            $item['id'],
            $item['groom_first_name'], 
            $item['groom_middle_name'], 
            $item['groom_last_name'], 
            $item['groom_suffix'], 
            $item['bride_first_name'], 
            $item['bride_middle_name'], 
            $item['bride_last_name'], 
            $item['bride_suffix'], 
            $item['marriage_date'], 
            $item['marriage_place'],  // Now treated as VARCHAR
            $item['groom_witness'], 
            $item['bride_witness'], 
            $item['status'] // Ensuring it matches the new VARCHAR type
        );

        $insertStmt->execute();
    }
}

// Close statements and connection
$checkStmt->close();
$insertStmt->close();
$conn->close();

// Display a browser alert and redirect to the Admin Dashboard
echo "<script>
    alert('Marriage Records Updated Successfully');
    window.location.href = 'https://citizenrequest.lgu2.com/admin/AdminDashboard.php';
</script>";

?>
