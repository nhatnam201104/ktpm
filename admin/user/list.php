<?php
// Cấu hình cơ sở dữ liệu
require_once('../../database/dbhelper.php');

$host = 'localhost';
$db_name = 'webnangcao';
$username = 'root';
$password = '';

header('Content-Type: application/json');

// Kết nối đến cơ sở dữ liệu
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Không thể kết nối đến cơ sở dữ liệu"]);
    exit();
}

// Lấy tham số từ URL
$id = isset($_GET['id']) ? $_GET['id'] : null;
$email = isset($_GET['email']) ? $_GET['email'] : null;
$phone = isset($_GET['mobile']) ? $_GET['mobile'] : null;
$name = isset($_GET['name']) ? $_GET['name'] : null;

try {
    if ($id) {
        // Lấy người dùng theo ID
        $stmt = $pdo->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->execute( ['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode([
                "error"=>0,
                "message"=>"success"
            ]);
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(["error" =>1,
             "message"=>"Người dùng không tồn tại"]);
        }
     } elseif ($name) {
     // Lấy người dùng theo email
     $stmt = $pdo->prepare("SELECT * FROM user WHERE name = :name");
     $stmt->execute(params: ['name' => $name]);
     $user = $stmt->fetch(PDO::FETCH_ASSOC);

     if ($user) {
        echo json_encode([
            "error"=>0,
            "message"=>"success"
        ]);
         echo json_encode($user);
     } else {
         http_response_code(404);
         echo json_encode(["error" =>1,
             "message"=>"Người dùng không tồn tại"]);
     }
     } elseif ($email) {
        // Lấy người dùng theo email
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->execute(params: ['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode([
                "error"=>0,
                "message"=>"success"
            ]);
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(["error" =>1,
             "message"=>"Người dùng không tồn tại"]);
        }
    } elseif ($phone) {
        // Lấy người dùng theo số điện thoại
        $stmt = $pdo->prepare("SELECT * FROM user WHERE mobile = :mobile");
        $stmt->execute(['mobile' => $phone]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode([
                "error"=>0,
                "message"=>"success"
            ]);
            echo json_encode($user);
        } else {
            http_response_code(404);
            echo json_encode(["error" =>1,
             "message"=>"Người dùng không tồn tại"]);
        }
    } else {
        // Lấy danh sách tất cả người dùng
        $stmt = $pdo->query("SELECT * FROM user");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode([
            "error"=>0,
            "message"=>"success"
        ]);
        echo json_encode($users);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
    echo json_encode(["error" => "Đã xảy ra lỗi khi truy vấn cơ sở dữ liệu"]);
}
?>
