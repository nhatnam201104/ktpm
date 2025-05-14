<?php
if(!empty($_POST)) {
	$id = getPost('id');
	$name = getPost('name');
	$email = getPost('email');
	$mobile = getPost('mobile');
	$address = getPost('address');
	$password = getPost('password');
	if($password != '') {
		$password = getSecurityMD5($password);
	}
	
	$created_at = $updated_at = date("Y-m-d H:i:s");

	$role_id = getPost('role_id');

	if($id > 0) {
		//update
		$sql = "select * from User where email = '$email' and id <> $id";
		$userItem = executeResult($sql, true);

		if($userItem != null) {
			$msg = 'Email này đã tồn tại trong tài khoản khác, vui lòng kiểm tra lại!!!';
		} else {
			if($password != '') {
				$sql = "update User set name = '$name', email = '$email', mobile = '$mobile', address = '$address', password = '$password', updated_at = '$updated_at', role_id = $role_id where id = $id";
			} else {
				$sql = "update User set name = '$name', email = '$email', mobile = '$mobile', address = '$address', updated_at = '$updated_at', role_id = $role_id where id = $id";
			}
			execute($sql);
			echo '<div class="alert alert-success" role="alert">Chúc mừng bạn đã sửa thông tin thành công!</div>';

			die();
		}
	} else {
		$sql = "select * from User where email = '$email' or mobile = '$mobile'";
		$userItem = executeResult($sql, true);
		//insert
		if($userItem == null) {
			//insert
			$sql = "insert into User(name, email, mobile, address, password, role_id, created_at, updated_at, is_active) 
            values ('$name', '$email', '$mobile', '$address', '$password', '$role_id', '$created_at', '$updated_at', 1)";
			execute($sql);
			echo '<div class="alert alert-success" role="alert">Chúc mừng bạn đã thêm tài khoản thành công!</div>';
			die();
		} else {
			//Tai khoan da ton tai -> failed
			$msg = 'Email hoặc SĐT đã được đăng ký, vui lòng kiểm tra lại!!!';
		}
	}
}