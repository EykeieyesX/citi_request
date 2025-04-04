<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json');

include '..config.php';
include '../FRONTEND/vendor/autoload.php';
include '../FRONTEND/vendor/firebase/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

$obj = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Decode the incoming JSON data
        $data = json_decode(file_get_contents("php://input"), true); // true to get an associative array

        // Access properties safely
        $username = isset($data['username']) ? $data['username'] : null;
        $password = isset($data['password']) ? $data['password'] : null;

        // Validate input
        if (!$username || !$password) {
            echo json_encode([
                'status' => 0,
                'message' => 'Username and password are required.',
            ]);
            exit();
        }

        // Select the user from the database
        $obj->select('census_credentials', '*', null, " AND username='{$username}'", null, null);
        $datas = $obj->getResult();

        // Check if user exists
        if (count($datas) == 0) {
            echo json_encode([
                'status' => 0,
                'message' => 'Invalid User',
            ]);
            exit();
        }

        // Get user data
        $user_data = $datas[0]; // Assuming only one user per username
        $stored_password = $user_data['password_hash']; // Password hash from database

        // Check the password
        if (!password_verify($password, $stored_password)) {
            echo json_encode([
                'status' => 0,
                'message' => 'Invalid Password',
            ]);
            exit();
        }

        // Prepare JWT payload
        $payload = [
            'iss' => "localhost",
            'aud' => 'localhost',
            'exp' => time() + (60 * 60), // 1 hour expiration
            'data' => [
                'username'  => $user_data['username'],
                'firstname' => $user_data['firstname'],
                'middlename' => $user_data['middlename'],
                'lastname' => $user_data['lastname'],
                'areaofcencusstreet' => $user_data['areaofcencusstreet']
            ],
        ];

        $secret_key = "CHRISTIAN_POGI"; // Secret key for JWT encoding
        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        // Return successful response with JWT
        echo json_encode([
            'status' => 1,
            'jwt'    => $jwt,
            'message' => 'Login Successfully',
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
?>
