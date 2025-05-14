<?php
class LoginController
{
    function form()
    {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $hpassword = md5(md5($password) . PRIVATE_KEY);
        // $hpassword = password_hash($password, PASSWORD_BCRYPT);
        // var_dump($password);
        // exit;
        $customerRepository =  new CustomerRepository();
        $customer = $customerRepository->findEmail($email);
        if ($customer) {
            if ($customer->getRoleID() == 5 || $customer->getRoleID() == 2) {
                $encodePassword = $customer->getPassword();
                if ($hpassword == $encodePassword) {
                    // if(password_verify($password,$encodePassword)){
                    if ($customer->getIsActive()) {
                        $_SESSION["success"] = "Đăng nhập thành công";
                        $_SESSION["email"] = $email;
                        $_SESSION["fullname"] = $customer->getName();
                    } else {
                        $_SESSION["error"] = "Tài khoản của bạn đã bị vô hiệu hóa. Xin vui lòng liên hệ Admin";
                    }
                }
                header("location:index.php");
                exit;
            } else {
                header("Location: ../admin/dashboard.php");
                exit;
            }
        }
        $_SESSION["error"] = "Vui lòng nhập lại email hoặc mật khẩu";
        header("location:index.php");
    }
    function google() {}

    function facebook() {}

    function logout()
    {
        unset($_SESSION["success"]);
        unset($_SESSION["email"]);
        unset($_SESSION["fullname"]);

        if (isset($_SESSION["error"])) {
            unset($_SESSION["error"]);
        }
        // Nếu muốn xóa nhiều session một cách ngắn gọn:
        // foreach (["success", "email", "fullname"] as $key) {
        //     unset($_SESSION[$key]);
        // }

        header("location: index.php");
        exit;
    }
}
