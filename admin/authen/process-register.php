<?php
$baseUrl = '../';
require_once('../../database/Database.php');
require_once('../../utils/App.php');
require_once('../../database/dbhelper.php');
require_once('../../database/config.php');

class Register extends App
{
    function __construct()
    {
        parent::__construct();
    }
    public function addNewUser()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $password = $_POST['password'];

        // Kiểm tra email đã tồn tại trong cơ sở dữ liệu
        $sql_exist = "SELECT * FROM user WHERE email = '$email'";
        $userExisted = executeResult($sql_exist, true);

        if ($userExisted) {
            // Email đã tồn tại, thông báo cho người dùng
            echo "Email đã tồn tại";
        } else {
            // Email chưa tồn tại, tiến hành thêm mới người dùng
            $created_at = $updated_at = date('Y-m-d H:i:s');
            $hashed_password = md5(md5($password).PRIVATE_KEY);
            $sql = "INSERT INTO user(name, email, mobile, password, role_id, created_at, updated_at, is_active) VALUES ('$name', '$email', '$mobile', '$hashed_password', 5, '$created_at', '$updated_at', 1)";
            $result = Database::getInstance()->execute($sql);
            $userId = mysqli_insert_id(Database::getInstance()->getConnection()); // ID của người dùng vừa được thêm mới
            $payload = [
                'user_id' => $userId,
                'created_at' => time()
            ];
            $token = \Firebase\JWT\JWT::encode($payload, PRIVATE_KEY, 'HS256');
            $sql = "INSERT INTO token (user_id, token, created_at) VALUES ($userId, '$token', NOW());";
            $resultTOKEN = Database::getInstance()->execute($sql);
            if ($resultTOKEN) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Đăng ký thành công',
                    'user_id' => $userId,
                    'token' => $token
                ]);
            } else {
                // Xử lý lỗi khi thêm mới không thành công
                echo "Có lỗi xảy ra khi thêm mới người dùng";
            }
        }
    }
    public function checkEmailExist($email)
    {
        $userExist = executeResult("select * from user where email='$email'", true);
        if ($userExist != null) {
            return 1;
        } else {
            return 0;
        }
    }

    // Kiểm tra xem yêu cầu là POST hay không
    public function checkEmail()
    {
        $email = $_POST['email'];
        $userExist = executeResult("select * from user where email='$email'", true);
        if ($userExist != null) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }
    public function checkPhone()
    {
        $mobile = $_POST['mobile'];
        $userExist = executeResult("select * from user where mobile='$mobile'", true);
        if ($userExist != null) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    }
}
$register = new Register();
