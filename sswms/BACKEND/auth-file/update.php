<?php

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Method:POST');
header('Content-Type:application/json');

include '../db/database.php';

$obj = new Database();

if ($_SERVER['REQUEST_METHOD'] == "PUT") {

    $user_id       = $_POST['user_id'];
    $user_name     = $_POST['user_name'];
    $user_type    = $_POST['user_type'];




    $user_id       = intval($data->user_id); // Assuming you're passing user_id for identifying the user
    $user_name     = htmlentities($data->user_name);
    $user_type    = htmlentities($data->user_type);

    // Check if the user exists
    $obj->select("bcp_lgu2_g67_users", "*", null, " AND user_type={$user_type}", null, null);
    $user = $obj->getResult();

    if (empty($user)) {
        echo json_encode([
            'status' => 0,
            'message' => 'User not found',
        ]);
    } else {
        // Update user details
        $updateData = [
            "user_type"     => $user_type,
        ];
        $obj->update('bcp_lgu2_g67_users', $updateData, "user_type={$user_type}");

        $result = $obj->getResult();

        if ($result) {
            echo json_encode([
                'status' => 1,
                'message' => 'User updated successfully',
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
