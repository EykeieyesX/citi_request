<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: https://citizenrequest.lgu2.com');
header('Access-Control-Allow-Methods: POST');
header('Content-Type: application/json');

include '../db/database.php';
include '../vendor/autoload.php';
include '../vendor/firebase/php-jwt/src/JWT.php';

use \Firebase\JWT\JWT;

$obj = new Database();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['user_email']) || !isset($data['user_password'])) {
            echo json_encode(['status' => 0, 'message' => 'Email and password are required.']);
            exit();
        }

        $user_email = $data['user_email'];
        $user_password = $data['user_password'];

        $obj->select('bcp_lgu2_g67_users', '*', null, " AND user_email='{$user_email}'", null, null);
        $datas = $obj->getResult();

        if (count($datas) == 0) {
            echo json_encode(['status' => 0, 'message' => 'Invalid User']);
            exit();
        }

        $user_data = $datas[0];

        if (!password_verify($user_password, $user_data['user_password'])) {
            echo json_encode(['status' => 0, 'message' => 'Invalid User']);
            exit();
        }

        $payload = [
            'iss' => "https://citizenrequest.lgu2.com",
            'aud' => "https://citizenrequest.lgu2.com",
            'exp' => time() + (60 * 60),
            'data' => [
                'user_name'  => $user_data['user_name'],
                'user_email' => $user_data['user_email'],
                'user_type'  => $user_data['user_type'],
                'user_fname' => $user_data['user_fname'],
                'user_lname' => $user_data['user_lname']
            ],
        ];

        $secret_key = "CHRISTIAN_POGI";
        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        echo json_encode([
            'status' => 1,
            'jwt'    => $jwt,
            'message' => 'Login Successfully'
        ]);
        exit();

    } catch (Exception $e) {
        echo json_encode(['status' => 0, 'message' => 'Error: ' . $e->getMessage()]);
        exit();
    }
} else {
    echo json_encode(['status' => 0, 'message' => 'Access Denied']);
    exit();
}
?>
