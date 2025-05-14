<?php 
require_once __DIR__ . '/../../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;  // Thêm dòng này
require_once "MailService.php";
class CustomerController {
	function info() {
		$customerRepository = new CustomerRepository();
		$customer = $customerRepository->findEmail($_SESSION["email"]);
		require "view/customer/info.php";
	}

	function updateInfo() {
		$customerRepository = new CustomerRepository();
		$customer = $customerRepository->findEmail($_SESSION["email"]);
		$customer->setName($_POST["fullname"]);
		$customer->setMobile($_POST["mobile"]);
		$currentPassword = $_POST["current_password"];
		$dbPassword = $customer->getPassword();
		$newPassword = $_POST["password"];
		$ConfirmnewPassword = $_POST["re-password"];
		if ($currentPassword && $newPassword && $ConfirmnewPassword) {
			if ($newPassword != $ConfirmnewPassword) {
				$_SESSION["error"] = "Xác nhận lại mật khẩu"; ;
				header("location: index.php?c=customer&a=info");
				exit;
			}
			//verify
			$hashedPassword = md5(md5($currentPassword) . PRIVATE_KEY);
			if ($hashedPassword === $dbPassword) {
				$encodePassword = md5(md5($newPassword) . PRIVATE_KEY);
				$customer->setPassword($encodePassword);
			}
			else {
				$_SESSION["error"] = "Mật khẩu hiện tại không đúng."; ;
				header("location: index.php?c=customer&a=info");
				exit;
			}
		}
		if ($customerRepository->update($customer)) {
			$_SESSION["name"] = $customer->getName();
			$_SESSION["success"] = "Đã cập nhật thông tin tài khoản thành công";
		}
		else {
			$_SESSION["error"] = $customerRepository->getError();
		}
		header("location: index.php?c=customer&a=info");
	}

	function shipping() {
		$customerRepository = new CustomerRepository();
		$customer = $customerRepository->findEmail($_SESSION["email"]);
		require "layout/variable_address.php";
		require "view/customer/shipping.php";
	}

	function updateShipping() {
		$customerRepository = new CustomerRepository();
		$customer = $customerRepository->findEmail($_SESSION["email"]);
		$customer->setName($_POST["fullname"]);
		$customer->setMobile($_POST["mobile"]);
		$customer->setWardId($_POST["ward"]);
		$customer->setAddress($_POST["address"]);
		if ($customerRepository->update($customer)) {
			$_SESSION["name"] = $customer->getName();
			$_SESSION["success"] = "Đã cập nhật thông tin giao hàng mặc định thành công";
		}
		else {
			$_SESSION["error"] = $customerRepository->getError();
		}
		header("location: index.php?c=customer&a=shipping");
	}

	function forgotPassword()
	{
		//Gởi email để reset tài khoản
		$email = $_POST["email"];
		//check email existing
		$customerRepository = new CustomerRepository();
		$customer = $customerRepository->findEmail($email);
		if (!$customer) {
			$_SESSION["error"] = "$email không tồn tại";
			header("location: index.php");
			exit;
		}
		$mailServer = new MailService();
		// print_r($customer);
		$key = PRIVATE_KEY;
		$payload = array(
			"email" => $email
		);
		$code = JWT::encode($payload, $key, 'HS256');
		$activeUrl = get_domain_site() . "/index.php?c=customer&a=resetPassword&code=$code";
		$content = "
		Chào $email, <br>
		Vui lòng click vào click vào link bên dưới để thiết lập lại password <br>
		<a href='$activeUrl'>Reset Password</a>
		";
		$mailServer->send($email, "Reset Password", $content);
		$_SESSION["success"] = "Vui lòng check email để reset password";
		header("location: index.php");
	}

	function resetPassword() {
		$code = $_GET["code"];
        try {
            $decoded = JWT::decode($code, new Key(PRIVATE_KEY, 'HS256'));
            $email = $decoded->email;
            $customerRepository = new CustomerRepository();
            $customer = $customerRepository->findEmail($email);
            if (!$customer) {
                $_SESSION["error"] = "Email $email không tồn tại";
                header("location: index.php");
            }
            require "view/customer/resetPassword.php";
			// echo "No error";
        }
        catch(Exception $e) {
            echo "You try hack!";
        }
	}

	function updatePassword() {
		$code = $_POST["code"];
        try {
            $decoded = JWT::decode($code, new Key(PRIVATE_KEY, 'HS256'));
            $email = $decoded->email;
            $customerRepository = new CustomerRepository();
            $customer = $customerRepository->findEmail($email);
			$newPassword = $_POST["password"];
			if($newPassword != $_POST["re-password"]){
				echo "Nah!";
			}
			else {
				// $hashNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);
				$customer->setPassword($newPassword);
				$customerRepository->update($customer);
				$_SESSION["success"] = "Password resets successfully";
				header("location: index.php");
				// echo "No error";
			}
        }
        catch(Exception $e) {
            echo "You try hack!";
        }
	}
}
