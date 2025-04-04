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
$apiUrl = "https://civilregistrar.lgu2.com/api/birth.php";

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
$checkStmt = $conn->prepare("SELECT id FROM birth_records WHERE id = ?");

// Prepare SQL query to insert new records
$insertStmt = $conn->prepare("INSERT INTO birth_records (id, child_first_name, child_middle_name, child_last_name, child_sex, child_date_of_birth, father_first_name, father_last_name, mother_first_name, mother_last_name, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

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
        $insertStmt->bind_param("issssssssss", 
            $item['id'],
            $item['child_first_name'], 
            $item['child_middle_name'], 
            $item['child_last_name'], 
            $item['child_sex'], 
            $item['child_date_of_birth'], 
            $item['father_first_name'], 
            $item['father_last_name'], 
            $item['mother_first_name'], 
            $item['mother_last_name'], 
            $item['status']
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
    alert('Updated Successfully');
    window.location.href = 'https://citizenrequest.lgu2.com/admin/AdminDashboard.php';
</script>";

?>
