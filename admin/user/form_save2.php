<?php
require_once('../../database/dbhelper.php');

// Hàm mã hóa mật khẩu
function getSecuritymd55($pwd)
{
    return md5(md5($pwd) . PRIVATE_KEY);
}

// Hàm lấy dữ liệu đầu vào từ `php://input` (cho PUT hoặc POST)
function getInputData()
{
    $inputData = file_get_contents("php://input");
    $parsedData = json_decode($inputData, true);

    if (json_last_error() != JSON_ERROR_NONE) {
        echo json_encode(["error" => "1", "message" => "Invalid JSON format"]);
     //    die();
    }

    return $parsedData;
}

// Lấy phương thức HTTP
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'PUT' || $method == 'POST') {
    // Lấy dữ liệu từ JSON input
    $data = getInputData();

    // Gán các giá trị từ input
    $id = $data['id'] ?? '';
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $mobile = $data['mobile'] ?? '';
    $address = $data['address'] ?? '';
    $password = $data['password'] ?? '';
    $role_id = $data['role_id'] ?? '';

    $created_at = $updated_at = date("Y-m-d H:i:s");

    if ($password !== '') {
        $password = getSecuritymd55($password);
    }

    // Kiểm tra input
    if ($name === '') {
        echo json_encode(["error" => "1", "message" => "empty_name"]);
        die();
    }
    if ($email === '') {
        echo json_encode(["error" => "1", "message" => "empty_email"]);
        die();
    }
    if ($mobile === '') {
        echo json_encode(["error" => "1", "message" => "empty_mobile"]);
        die();
    }
    if ($address === '') {
        echo json_encode(["error" => "1", "message" => "empty_address"]);
        die();
    }
    if ($password === '') {
        echo json_encode(["error" => "1", "message" => "empty_password"]);
        die();
    }
    if ($role_id === '') {
        echo json_encode(["error" => "1", "message" => "empty_role"]);
        die();
    }

    if ($id > 0) {
        // Update
        $sql = "select * from User where email = '$email' and id <> $id";
        $userItem = executeResult($sql, true);

        if ($userItem != null) {
            echo json_encode(["error" => "1", "message" => "email_existed"]);
            die();
        } else {
            if ($password !== '') {
                $sql = "update User set name = '$name', email = '$email', mobile = '$mobile', address = '$address', password = '$password', updated_at = '$updated_at', role_id = $role_id where id = $id";
            } else {
                $sql = "update User set name = '$name', email = '$email', mobile = '$mobile', address = '$address', updated_at = '$updated_at', role_id = $role_id where id = $id";
            }
            execute($sql);
            echo json_encode(["error" => "0", "message" => "success"]);
            die();
        }
    } else {
        // Insert
        $sql = "select * from User where email = '$email'";
        $userItem = executeResult($sql, true);

        if (empty($userItem)) {
            $sql = "insert into User(name, email, mobile, address, password, role_id, created_at, updated_at, is_active) 
                values ('$name', '$email', '$mobile', '$address', '$password', '$role_id', '$created_at', '$updated_at', 1)";
            execute($sql);
            echo json_encode(["error" => "0", "message" => "success"]);
            die();
        } else {
            echo json_encode(["error" => "1", "message" => "email_used"]);
            die();
        }
    }
} else {
//     echo json_encode(["error" => "1", "message" => "Invalid request method"]);
//     die();
}
