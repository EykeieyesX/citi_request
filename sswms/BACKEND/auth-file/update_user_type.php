<?php

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Method:POST');
header('Content-Type:application/json');

include '../db/database.php';

// Instantiate Database class
$obj = new Database();

// Read raw POST data and decode JSON
$data = json_decode(file_get_contents("php://input"));

// Check if data is valid
if ($_SERVER['REQUEST_METHOD'] == "POST" && $data) {

    // Get data and sanitize
    $microtime = microtime(true);
    $milliseconds = round($microtime * 1000);


    $user_id        = isset($data->user_id)             ? htmlentities($data->user_id) : null;
    $user_type            = isset($data->user_type)     ? htmlentities($data->user_type) : null;
    
    $action              = isset($data->action) ? htmlentities($data->action) : "insert";

    if($action=="insert"){
        $user_id    = $milliseconds;
    }
    else if($action=="update" || $action=="delete"){
        $user_id = isset($data->user_id) ? htmlentities($data->user_id) : null;
    }

    $data = [
        "user_id"           => $user_id, 
        "user_type"         => $user_type,
    ];

    if($action=="insert"){
        $data['user_id'] = $user_id;
        $obj->insert('bcp_lgu2_g67_users', $data);
    }
    else if($action=="update"){
        $obj->update('bcp_lgu2_g67_users', $data, "user_id = '{$user_id}' ");
    }

    else if($action=="delete"){
        $obj->delete('bcp_lgu2_g67_users', " user_id = '{$user_id}' ");
    }

    // Check result and respond accordingly
    $data = $obj->getResult();

    if (isset($data[0]) && $data[0] == 1) {
        echo json_encode([
            'status' => 1,
            'message' => 'Successfully Request',
        ]);
    } else {
        echo json_encode([
            'status' => 0,
            'message' => 'Server problem, could not add user',
        ]);
    }

} else {
    // Handle invalid access
    echo json_encode([
        'status' => 0,
        'message' => 'Access denied or invalid request',
    ]);
}

?>
