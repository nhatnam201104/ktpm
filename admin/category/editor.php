<?php
$name = 'Thêm/Sửa Danh Mục';
$baseUrl = '../';
require_once('../layouts/header.php');
if ($_SESSION["user"]["role_id"] != 3 && $_SESSION["user"]["role_id"] != 2) {
    echo 'Cannot access';
    die();
}
$id = $name = '';

$id = getGet('id');
if ($id != '' && $id > 0) {
    $sql = "SELECT * FROM category WHERE id = '$id'  ";
    $categoryItem = executeResult($sql, true);
    if ($categoryItem != null) {
        $name = $categoryItem[0]['name'];
    } else {
        $id = 0;
    }
} else {
    $id = 0;
}
?>

<div class="row" style="margin-top: 20px;">
    <div class="col-md-12 table-responsive">
        <h3>Thêm/Sửa Danh Mục</h3>
        <div class="panel panel-primary">
            <div class="panel-body">
                <!-- Form không gửi trực tiếp mà sử dụng JavaScript -->
                <form id="categoryForm">
                    <div class="form-group">
                        <label for="name">Tên Danh Mục:</label>
                        <input required="true" type="text" class="form-control" id="name" name="name" value="<?= $name ?>">
                        <input type="hidden" id="id" name="id" value="<?= $id ?>">
                    </div>
                    <button type="button" class="btn btn-success" id="saveButton">
                        Lưu Danh Mục
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="alert-overlay" id="alert-overlay">
    <div class="alert-dialog">
        <h2>Thông Báo</h2>
        <p id="alert-message"></p>
        <p>Giá trị alert: <span id="alert-value"></span></p>
        <button onclick="hideAlert()">Đóng</button>
    </div>
</div>
<script>
    document.getElementById('saveButton').addEventListener('click', function() {
        const id = document.getElementById('id').value;
        const name = document.getElementById('name').value;


        // Kiểm tra tên danh mục không chứa ký tự đặc biệt
        const specialCharRegex = /^[a-zA-Z0-9\s]+$/; // Chỉ cho phép chữ cái, số và khoảng trắng
        if (name.trim() !== "" && !specialCharRegex.test(name)) {
            alert('Tên danh mục không được chứa ký tự đặc biệt!');
            return; // Dừng xử lý nếu không hợp lệ
        }

        const method = id > 0 ? 'PUT' : 'POST'; // Xác định phương thức
        const url = './form_save.php';

        // Tạo dữ liệu gửi dưới dạng JSON
        const data = JSON.stringify({
            id: parseInt(id) || 0,
            name: name
        });

        fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json', // Đặt kiểu dữ liệu là JSON
                },
                body: data, // Body là chuỗi JSON
            })
            .then(response => response.json())
            .then(result => {
                if (result.code === 200) {
                    showAlert(result.message, '');
                    // Sau khi lưu thành công, bạn có thể reload trang hoặc chuyển hướng
                    // location.reload();
                } else {
                    showAlert(result.message, '');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Lỗi khi gửi yêu cầu đến server!');
            });
    });

    function showAlert(message, value) {
        document.getElementById('alert-message').textContent = message;
        document.getElementById('alert-value').textContent = value;
        document.getElementById('alert-overlay').style.opacity = '1';
        document.getElementById('alert-overlay').style.visibility = 'visible';
    }

    function hideAlert() {
        document.getElementById('alert-overlay').style.opacity = '0';
        document.getElementById('alert-overlay').style.visibility = 'hidden';
    }
</script>


<?php
require_once('../layouts/footer.php');
?>