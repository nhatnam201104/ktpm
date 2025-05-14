<?php
require_once('../../database/dbhelper.php');
// Kiểm tra phương thức HTTP
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Xử lý thêm mới danh mục
    $input = json_decode(file_get_contents('php://input'), true);
    $name = $input['name'] ?? '';

    if (empty($name)) {
        http_response_code(400);
        echo json_encode(["code" => 400, "message" => "Tên danh mục bị để trống"]);
        die();
    }

    $sql = "SELECT * FROM category WHERE name = '$name'";
    $category = executeResult($sql, true);
    if ($category != null) {
        echo json_encode(["code" => 400, "message" => "Tên danh mục bị trùng"]);
        die();
    }

    $sql = "INSERT INTO category (name) VALUES ('$name')";
    execute($sql);
    echo json_encode(["code" => 200, "message" => "Thêm danh mục thành công"]);
    die();
} elseif ($method === 'PUT') {
    // Lấy dữ liệu PUT từ body (dạng JSON)
    $json = file_get_contents("php://input");
    $put_vars = json_decode($json, true); // Chuyển JSON thành mảng PHP

    // Lấy dữ liệu từ mảng
    $id = $put_vars['id'] ?? 0;
    $name = $put_vars['name'] ?? '';

    // Kiểm tra tính hợp lệ
    if (empty($name)) {
        http_response_code(400);
        echo json_encode(["code" => 400, "message" => "Tên danh mục bị để trống"]);
        die();
    }

    // Cập nhật danh mục trong cơ sở dữ liệu
    $sql = "UPDATE category SET name = '$name' WHERE id = $id";
    execute($sql);

    // Trả về phản hồi
    echo json_encode(["code" => 200, "message" => "Sửa danh mục thành công"]);
    die();
} else {
    // Phương thức không hợp lệ
    http_response_code(405);
    echo json_encode(["code" => 405, "message" => "Phương thức không được hỗ trợ"]);
    die();
}
?>