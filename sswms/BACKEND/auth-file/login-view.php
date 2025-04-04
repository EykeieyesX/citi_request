<?php
header('Content-Type: application/json');

// Dummy data to simulate database response
$response = [
    'id' => '12345',
    'name' => 'John Doe',
    'email' => 'johndoe@example.com',
];

// Send JSON response
echo json_encode($response);
?>
