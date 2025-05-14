<?php
// header('Content-Type: application/json; charset=utf-8');


require_once('../../database/dbhelper.php');

function getSecuritymd55($pwd)
{
    return md5(md5($pwd) . PRIVATE_KEY);
}
function getInputData() {
    $inputData = file_get_contents("php://input");
    $parsedData = json_decode($inputData, true);
    return $parsedData ? $parsedData : [];
}
if (!empty($_POST)) {
    // $xmethod = $_POST['xmethod']?? 'POST';
    // $id = $_POST['id'] ?? '';
    // $name = $_POST['name'] ?? '';
    // $email = $_POST['email'] ??'';
    // $mobile = $_POST['mobile'] ??'';
    // $address = $_POST['address'] ??'';
    // $password = $_POST['password'] ??'';
    // $role_id = $_POST['role_id'] ??'';
    file_put_contents('php://stdout', json_encode(['method' => $_SERVER['REQUEST_METHOD'], 'input' => file_get_contents('php://input')]) . PHP_EOL);
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === 'PUT') {
        // Lấy dữ liệu từ PUT
        $_PUT = getInputData();

        $id = $_PUT['id'] ?? '';
        $name = $_PUT['name'] ?? '';
        $email = $_PUT['email'] ?? '';
        $mobile = $_PUT['mobile'] ?? '';
        $address = $_PUT['address'] ?? '';
        $password = $_PUT['password'] ?? '';
        $role_id = $_PUT['role_id'] ?? '';
    } elseif ($method === 'POST') {
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $mobile = $_POST['mobile'] ?? '';
        $address = $_POST['address'] ?? '';
        $password = $_POST['password'] ?? '';
        $role_id = $_POST['role_id'] ?? '';
    } else {
        echo json_encode(["error" => "1", "message" => "Invalid request method"]);
        die();
    }

    $created_at = $updated_at = date("Y-m-d H:i:s");

    if ($password != '') {
        $password = getSecuritymd55($password);
    }
    // Kiểm tra lỗi input
    if ($name == '') {
        echo json_encode(["error" => "1", "message" => "empty_name"]);
        die();
    }
    if ($email == '') {
        echo json_encode(["error" => "1", "message" => "empty_email"]);
        die();
    }
    if ($mobile == '') {
        echo json_encode(["error" => "1", "message" => "empty_mobile"]);
        die();
    }
    if ($address == '') {
        echo json_encode(["error" => "1", "message" => "empty_address"]);
        die();
    }
    if ($password == '') {
        echo json_encode(["error" => "1", "message" => "empty_password"]);
        die();
    }
    if ($role_id == '') {
        echo json_encode(["error" => "1", "message" => "empty_role"]);
        die();
    }

    if ($id >0) {
        // Update
        $sql = "select * from User where email = '$email' and id <> $id";
        $userItem = executeResult($sql, true);

        if ($userItem != null) {
            echo json_encode(["error" => "1", "message" => "email_existed"]);
            die();
        } else {
            if ($password != '') {
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

        if ($userItem == null) {
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
}
