<?php
$api_url = "https://backend-api-5m5k.onrender.com/api/cencus";

$response = file_get_contents($api_url);
$data = json_decode($response, true);

echo "<pre>";
print_r($data); // Print the full API response
echo "</pre>";
?>