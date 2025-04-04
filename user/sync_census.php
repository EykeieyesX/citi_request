<?php
require 'config.php'; // Database connection

// API URL to fetch the census data
$api_url = "https://backend-api-5m5k.onrender.com/api/cencus";

// Fetch data from the API
$response = file_get_contents($api_url);
$data = json_decode($response, true);

// Check if the data is valid
if (!$data || !isset($data['data']) || !is_array($data['data'])) {
    die("❌ Failed to fetch or parse data from API.");
}

// Start a transaction for better performance
$conn->begin_transaction();

// Loop through each citizen data and insert it into the database
foreach ($data['data'] as $citizen) {
    $api_id = $citizen["_id"];
    $firstname = $citizen["firstname"];
    $middlename = $citizen["middlename"];
    $lastname = $citizen["lastname"];
    $areaofcencusstreet = $citizen["areaofcencusstreet"];

    // Generate the default email using firstname and the first 2 letters of lastname
    $email = strtolower($firstname) . substr(strtolower($lastname), 0, 2) . "@gmail.com"; 

    // Check if the api_id already exists to prevent duplicates
    $stmt = $conn->prepare("SELECT COUNT(*) FROM census_credentials WHERE api_id = ?");
    $stmt->bind_param("s", $api_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count == 0) { // Insert only if the entry does not already exist
        // Get the next available username (Citizen00001, Citizen00002, etc.)
        $stmt = $conn->prepare("SELECT COUNT(*) FROM census_credentials");
        $stmt->execute();
        $stmt->bind_result($user_count);
        $stmt->fetch();
        $stmt->close();

        $next_number = str_pad($user_count + 1, 5, "0", STR_PAD_LEFT);
        $username = "Citizen" . $next_number;

        // Generate a default password using "Citizen" + first two letters of the last name
        $default_password = "Citizen" . substr($lastname, 0, 2); // For example: CitizenSa for last name Santos
        $password_hash = password_hash($default_password, PASSWORD_BCRYPT);

        // Insert the data into the table
        $stmt = $conn->prepare("INSERT INTO census_credentials (api_id, firstname, middlename, lastname, areaofcencusstreet, username, password_hash, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $api_id, $firstname, $middlename, $lastname, $areaofcencusstreet, $username, $password_hash, $email);
        $stmt->execute();
        $stmt->close();

        // Get the auto-incremented database ID
        $inserted_id = $conn->insert_id;

        // Display the synced data
        echo "✅ Inserted: \n";
        echo "Username: $username\n";
        echo "Password: $default_password\n";
        echo "Email: $email\n";  // Display the generated email
        echo "Name: $firstname $middlename $lastname\n";
        echo "API ID: $api_id\n";
        echo "Database ID: $inserted_id\n";
        echo "---------------------------------\n";
    } else {
        // Skip the record if it already exists in the database
        echo "⚠️ Skipping existing entry with API ID: $api_id\n";
    }
}

// Commit the transaction
$conn->commit();

// Output success message
echo "🎉 Data sync completed!";
$conn->close();
?>
