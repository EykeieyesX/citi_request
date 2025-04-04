<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json');

include '../db/database.php';
include '../vendor/autoload.php';
include '../vendor/firebase/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

$obj = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Decode the incoming JSON data
        $data = json_decode(file_get_contents("php://input"), true); // true for associative array

        // Get and sanitize data
        $user_fname     = isset($data['user_fname']) ? $data['user_fname'] : null;
        $user_lname     = isset($data['user_lname']) ? $data['user_lname'] : null;
        $user_email     = isset($data['user_email']) ? $data['user_email'] : null;
        $user_address   = isset($data['user_address']) ? $data['user_address'] : null;
        $user_number    = isset($data['user_number']) ? $data['user_number'] : null;
        $user_password  = isset($data['user_password']) ? $data['user_password'] : null;

        // Validate required fields
        if (!$user_email || !$user_password || !$user_fname || !$user_lname || !$user_address) {
            echo json_encode([
                'status' => 0,
                'message' => 'All fields are required.',
            ]);
            exit();
        }

        // Check if the email already exists
        $obj->select("bcp_lgu2_g67_users", "user_email", null, " AND user_email='{$user_email}'", null, null);
        $is_email = $obj->getResult();

        if (isset($is_email[0]['user_email']) && $is_email[0]['user_email'] === $user_email) {
            echo json_encode([
                'status' => 2,
                'message' => 'Email already exists',
            ]);
            exit();
        }

        // Hash the password
        $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);

        // Enforce default user_type as 'User'
        $user_type = 'USER';

        // Generate a unique user_id
        $user_id = uniqid('user_', true);

        // Insert new user data into the database
        $obj->insert('bcp_lgu2_g67_users', [
            "user_id"        => $user_id,
            "user_fname"     => $user_fname,
            "user_lname"     => $user_lname,
            "user_email"     => $user_email,
            "user_address"   => $user_address,
            "user_number"    => $user_number,
            "user_password"  => $hashed_password,
            "user_type"      => $user_type,
        ]);

        $data = $obj->getResult();

        if (isset($data[0]) && $data[0] == 1) {
            // Create JWT payload
            $payload = [
                'iss' => 'localhost',
                'aud' => 'localhost',
                'exp' => time() + (60 * 60), // 1-hour expiration
                'data' => [
                    'user_id'    => $user_id,
                    'user_fname' => $user_fname,
                    'user_lname' => $user_lname,
                    'user_email' => $user_email,
                    'user_type'  => $user_type,
                ],
            ];

            $secret_key = "CHRISTIAN_POGI"; // Replace with your actual secret key
            $jwt = JWT::encode($payload, $secret_key, 'HS256');

            // Return success with JWT
            echo json_encode([
                'status' => 1,
                'message' => 'User registered successfully',
                'jwt'    => $jwt,
                'user_id' => $user_id, // Optionally return the user ID
            ]);
        } else {
            echo json_encode([
                'status' => 0,
                'message' => 'Server problem, could not add user',
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'status' => 0,
            'message' => 'An error occurred: ' . $e->getMessage(),
        ]);
    }
} else {
    echo json_encode([
        'status' => 0,
        'message' => 'Access denied or invalid request',
    ]);
}
?>
