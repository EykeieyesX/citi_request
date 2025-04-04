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
$apiUrl = "https://civilregistrar.lgu2.com/api/death.php"; // Updated to the death records API

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
$checkStmt = $conn->prepare("SELECT id FROM death_records WHERE id = ?");

// Prepare SQL query to insert new records
$insertStmt = $conn->prepare("INSERT INTO death_records (id, deceased_first_name, deceased_middle_name, deceased_last_name, deceased_dob, date_of_death, place_of_death, cause_of_death, informant_name, relationship_to_deceased, informant_contact, disposition_method, disposition_date, disposition_location, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

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
        $insertStmt->bind_param(
            "i" . str_repeat("s", 14), // 1 integer + 14 strings
            $item['id'],
            $item['deceased_first_name'], 
            $item['deceased_middle_name'], 
            $item['deceased_last_name'], 
            $item['deceased_dob'], 
            $item['date_of_death'], 
            $item['place_of_death'], 
            $item['cause_of_death'], 
            $item['informant_name'], 
            $item['relationship_to_deceased'], 
            $item['informant_contact'], 
            $item['disposition_method'], 
            $item['disposition_date'], 
            $item['disposition_location'], 
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
