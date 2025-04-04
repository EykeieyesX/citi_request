<?php
require 'config.php'; // Database connection

// Test insert statement
$sql = "INSERT INTO census_credentials (api_id, firstname, lastname, username, password_hash) VALUES ('test123', 'Test', 'User', 'TestUser123', 'TestPassHash')";

if ($conn->query($sql) === TRUE) {
    echo "✅ Test insert successful!";
} else {
    echo "❌ Test insert failed: " . $conn->error;
}

$conn->close();
?>
