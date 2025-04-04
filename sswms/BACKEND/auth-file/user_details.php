<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json');

include '../db/database.php';
include '../vendor/autoload.php';

use \Firebase\JWT\JWT;

$obj = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        // Decode the incoming JSON data
      
        // Access properties safely
        $link = isset($_GET['link']) ? $_GET['link'] : null;
      
        // Validate input
        if (!$link) {
            echo json_encode([
                'status' => 0,
                'message' => 'User not existing.',
            ]);
            exit();
        }

        // Select the user from the database
        $obj->select('bcp_lgu2_g67_users', 'user_fname, user_lname, user_number,  user_address, user_email, user_type, profile_picture', null, " AND (user_link='{$link}' OR user_email='{$link}')");

        
        $datas = $obj->getResult();

        // Check if user exists
        if (count($datas) == 0) {
            echo json_encode([
                'status' => 0,
                'message' => 'User not existing',
            ]);
            exit();
        }

      
        // Return successful response with JWT
        echo json_encode([
            'status' => 1,
            'message' => $datas,
        ]);
        exit();

    } catch (ErrorException $e) {
        echo json_encode([
            'status' => 0,
            'message' => 'An error occurred: ' . $e->getMessage(),
        ]);
    }

} else {
    echo json_encode([
        'status' => 0,
        'message' => 'Access Denied',
    ]);
}