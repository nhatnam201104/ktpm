<?php
// require_once('../../utils/utility.php');
require_once('../../database/dbhelper.php');

function moveUploadedFile($file)
{
    // Kiểm tra xem có lỗi upload không
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    // Kiểm tra kích thước file (ví dụ giới hạn 5MB)
    $maxFileSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxFileSize) {
        return false;
    }

    // Kiểm tra loại file
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        return false;
    }

    // Tạo tên file mới để tránh trùng lặp
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFileName = uniqid() . '.' . $extension;

    // Đường dẫn thư mục lưu file
    $uploadDir = 'uploads/'; // Thay đổi đường dẫn theo project của bạn
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Đường dẫn đầy đủ của file
    $destination = $uploadDir . $newFileName;

    // Di chuyển file
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $destination; // Trả về đường dẫn file nếu thành công
    }

    return false; // Trả về false nếu có lỗi
}

if (isset($_POST['upload_img'])) {
    $id = $_POST['id'] ?? '';

    // Xử lý file ảnh
    $featured_image = '';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['featured_image']['tmp_name'];
        $image_data = file_get_contents($tmp_name);
        $mime_type = mime_content_type($tmp_name); // Tự động lấy loại ảnh: png, jpeg, gif...
        $base64 = base64_encode($image_data);
        $featured_image = "data:image/png;base64,$base64";
    } else {
        http_response_code(400);
        echo json_encode(['code' => 400, 'message' => 'Hình ảnh trống']);
        die();
    }

    // Cập nhật database
    $updated_at = date("Y-m-d H:i:s");
    $sql = "UPDATE Product SET featured_image = '$featured_image', updated_at = '$updated_at' WHERE id=$id";
    execute($sql);

    echo json_encode(['code' => 200, 'message' => 'success']);
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý thêm sản phẩm
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    $discount_id = $_POST['discount_id'] ?? '';
    $description = $_POST['description'] ?? '';
    $created_date = $updated_at = date("Y-m-d H:i:s");

    // Xử lý file ảnh
    $featured_image = '';
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['featured_image']['tmp_name'];
        $image_data = file_get_contents($tmp_name);
        $base64 = base64_encode($image_data);
        $featured_image = 'data:image/png;base64,' . $base64;
    } else {
        http_response_code(400);
        echo json_encode(['code' => 400, 'message' => 'Hình ảnh trống']);
        die();
    }

    // Kiểm tra dữ liệu khác
    if ($name == '') {
        http_response_code(400);
        echo json_encode(['code' => 400, 'message' => 'Tên sản phẩm trống']);
        die();
    }
    if ($category_id == '') {
        http_response_code(400);
        echo json_encode(['code' => 400, 'message' => 'Loại sản phẩm trống']);
        die();
    }
    if ($discount_id == '') {
        http_response_code(400);
        echo json_encode(['code' => 400, 'message' => 'Mã giảm giá trống']);
        die();
    }
    if ($description == '') {
        http_response_code(400);
        echo json_encode(['code' => 400, 'message' => 'Miêu tả trống']);
        die();
    }

    // Thực thi câu lệnh SQL
    $sql = "INSERT INTO Product (discount_id, brand_id, featured_image, name, description, updated_at, created_date, category_id) 
            VALUES ('$discount_id', '1', '$featured_image', '$name', '$description', '$updated_at', '$created_date', '$category_id')";
    execute($sql);
    echo json_encode(['code' => 200, 'message' => 'success']);
    die();
}


if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $put_vars = json_decode(file_get_contents("php://input"), true);
    $id = $put_vars['id'] ?? 0;
    $name = $put_vars['name'] ?? '';
    $category_id = $put_vars['category_id'] ?? '';
    $discount_id = $put_vars['discount_id'] ?? '';
    $description = $put_vars['description'] ?? '';
    if (empty($name)) {
        http_response_code(400);
        echo json_encode(['code' => 400, 'message' => 'Tên sản phẩm trống']);
        die();
    }
    if ($category_id == '') {
        http_response_code(400);
        echo json_encode(['code' => 400, 'message' => 'Loại sản phẩm trống']);
        die();
    }
    if ($discount_id == '') {
        http_response_code(400);
        echo json_encode(['code' => 400, 'message' => 'Mã giảm giá trống']);
        die();
    }
    if ($description == '') {
        http_response_code(400);
        echo json_encode(['code' => 400, 'message' => 'Miêu tả trống']);
        die();
    }

    // Tiếp tục xử lý thêm/sửa sản phẩm
    $updated_at = date("Y-m-d H:i:s");
    $sql = "UPDATE Product SET discount_id='$discount_id', name='$name', description='$description', 
            updated_at='$updated_at', category_id='$category_id' WHERE id=$id";
    execute($sql);
    echo json_encode(['code' => 200, 'message' => 'success']);
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Xử lý thêm sản phẩm
    $id = $_GET['id'];
    if ($id == '') {
        http_response_code(400);
        echo json_encode(['code' => 400, 'message' => 'ID trống']);
        die();
    }
    $sql = "SELECT * FROM Product WHERE id = $id";
    $productItem = executeResult($sql, true);
    if ($productItem == null) {
        http_response_code(400);
        echo json_encode(['code' => 400, 'message' => 'Sản phẩm không tồn tại']);
        die();
    }
    echo json_encode(['code' => 200, 'message' => 'success', 'data' => $productItem]);
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $put_vars = json_decode(file_get_contents("php://input"), true);
    $id = $put_vars['id'];
    if (empty($id) || !is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['code' => 400, 'message' => 'ID không hợp lệ']);
        die();
    }
    $sql = "DELETE FROM Product WHERE id = ?";
    $stmt = execute2($sql, [$id]);
    if ($stmt) {
        echo json_encode(['code' => 200, 'message' => 'success']);
    } else {
        http_response_code(404);
        echo json_encode(['code' => 404, 'message' => 'Sản phẩm không tồn tại']);
    }
    die();
}
?>