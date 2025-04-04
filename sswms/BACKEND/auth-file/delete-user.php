<?php

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Method:POST');
header('Content-Type:application/json');

include './db/database.php';

$obj = new Database();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $data = json_decode(file_get_contents("php://input", true));

    $user_id     = $_POST['user_id'];

    $obj->select("users", "*", null, " AND user_id={$user_id}", null, null);
    $user = $obj->getResult();

    if (empty($user)) {
        echo json_encode([
            'status' => 0,
            'message' => 'User not found',
        ]);
    } else {
        // Delete the user
        $obj->delete('users', "user_id={$user_id}");

        $result = $obj->getResult();

        if ($result) {
            echo json_encode([
                'status' => 1,
                'message' => 'User deleted successfully',
            ]);
        } else {
            echo json_encode([
                'status' => 0,
                'message' => 'Server problem',
            ]);
        }
    }
} else {
    echo json_encode([
        'status' => 0,
        'message' => 'Access denied',
    ]);
}
