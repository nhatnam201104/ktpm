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

// Kiểm tra phương thức HTTP
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Lấy dữ liệu từ URL hoặc body
     $data = json_decode(file_get_contents("php://input"), true);
     $id = isset($data['id']) ? $data['id'] : null;

    if ($id) {
        try {
            // Xóa người dùng dựa vào ID
            $stmt = $pdo->prepare("DELETE FROM user WHERE id = :id");
            $stmt->execute(['id' => $id]);

            if ($stmt->rowCount() > 0) {
                echo json_encode(["message" => "Người dùng đã được xóa thành công"]);
            } else {
                http_response_code(404);
                echo json_encode(["error" =>1,
             "message"=>"Người dùng không tồn tại"]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["error" => "Đã xảy ra lỗi khi xóa người dùng"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Thiếu tham số ID"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Phương thức không được hỗ trợ"]);
}
?>
