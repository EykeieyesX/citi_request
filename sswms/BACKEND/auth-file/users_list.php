<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Content-Type: application/json');

require '../db/database.php';
$obj = new Database();

try {
    if ($_SERVER["REQUEST_METHOD"] == 'GET') {

        $data_body = json_decode(file_get_contents("php://input"));
        $obj->select('bcp_lgu2_g67_users', "*");
        $list = $obj->getResult();
        
        echo json_encode([
            'status' => 1,
            'message' => $list,
        ]);

    } else {
        http_response_code(405);
        echo json_encode([
            'status' => 0,
            'message' => 'Method Not Allowed'
        ]);
    }
} catch (Exception $e) {
    // Internal Server Error
    http_response_code(500); // Internal Server Error
    echo json_encode([
        'status' => 0,
        'message' => 'Internal Server Error: ' . $e->getMessage()
    ]);
}