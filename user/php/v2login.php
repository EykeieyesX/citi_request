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
            $user_email = isset($data['user_email']) ? $data['user_email'] : null;
            $user_password = isset($data['user_password']) ? $data['user_password'] : null;
 
            // Validate input
            if (!$user_email || !$user_password) {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Email and password are required.',
                ]);
                exit();
            }

            // Select the user from the database
            $obj->select('usercredentials', '*', null, " AND user_email='{$user_email}'", null, null);
            $datas = $obj->getResult();

            // Check if user exists
            if (count($datas) == 0) {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Invalid User',
                ]);
                exit();
            }

            foreach ($datas as $data) {
                $user_email = $data['user_email'];
                $user_name  = $data['user_name'];
                $user_type  = $data['user_type'];
                $user_fname = $data['user_fname'];
                $user_lname = $data['user_lname'];
            }

            // Check the password
            $user_data = $datas[0]; // Assuming only one user per email
            if (!password_verify($user_password, $user_data['user_password'])) {
                echo json_encode([
                    'status' => 0,
                    'message' => 'Invalid User',
                ]);
                exit();
            }

            $payload = [
                'iss' => "localhost",
                'aud' => 'localhost',
                'exp' => time() + (60 * 60),
                'data' => [
                    'user_name'  => $user_data['user_name'],
                    'user_email' => $user_data['user_email'],
                    'user_type'  => $user_data['user_type'],
                    'user_fname'  => $user_data['user_fname'],
                    'user_lname'  => $user_data['user_lname']
                ],
            ];

            $secret_key = "CHRISTIAN_POGI";
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