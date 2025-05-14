<?php
function fixSqlInjection($str) {
	// abc\okok -> abc\\okok
	//abc\okok (user) -> abc\okok (server) -> sql (abc\okok) -> xuat hien ky tu \ -> ky tu dac biet -> error query
	//abc\okok (user) -> abc\okok (server) -> convert -> abc\\okok -> sql (abc\\okok) -> chinh xac
	$str = str_replace('\\', '\\\\', $str);
	//abc'okok -> abc\'okok
	//abc'okok (user) -> abc'okok (server) -> sql (abc'okok) -> xuat hien ky tu \ -> ky tu dac biet -> error query
	//abc'okok (user) -> abc'okok (server) -> convert -> abc\'okok -> sql (abc\'okok) -> chinh xac
	$str = str_replace('\'', '\\\'', $str);

	return $str;
}

function authenToken() {
	if (isset($_SESSION['user'])) {
		return $_SESSION['user'];
	}

	$token = getCOOKIE('token');
	if (empty($token)) {
		return null;
	}

	$sql    = "select user.* from user, login_tokens where user.id = login_tokens.id_user and login_tokens.token = '$token'";
	$result = executeResult($sql);

	if ($result != null && count($result) > 0) {
		$_SESSION['user'] = $result[0];

		return $result[0];
	}

	return null;
}

function getPOST($key) {
	$value = '';
	if (isset($_POST[$key])) {
		$value = $_POST[$key];
	}
	return fixSqlInjection($value);
}

function getCOOKIE($key) {
	$value = '';
	if (isset($_COOKIE[$key])) {
		$value = $_COOKIE[$key];
	}
	return fixSqlInjection($value);
}

function getGET($key) {
	$value = '';
	if (isset($_GET[$key])) {
		$value = $_GET[$key];
	}
	return fixSqlInjection($value);
}

function getSecuritymd5($pwd)
{
	return md5(md5($pwd).PRIVATE_KEY);
}
function getUserToken(){
	if(isset($_SESSION['user'])){
		return $_SESSION['user'];
	}
	$token =getCOOKIE('token');
	$sql="select * from token where token = '$token'";
	$item= executeResult($sql,true);
	if($item != null)
	{	

		$userID = 	$item[0]['user_id'];
		$sql= "select * from user where id= '$userID' and is_active=0";
		$item=executeResult($sql,true);
		if($item != null)
		{
			 $_SESSION['user']=$item;
			 return $item;
		}
		
	}
	return null;
}

function moveFile($key, $rootPath = "../") {
    if(!isset($_FILES[$key]) || !isset($_FILES[$key]['name']) || $_FILES[$key]['name'] == '') {
        return '';
    }

    $pathTemp = $_FILES[$key]["tmp_name"];

    $filename = $_FILES[$key]['name'];
    //filename -> remove special character, ..., ...

    $newPath = $rootPath . "assets/img/" . $filename; // Đường dẫn đã điều chỉnh
    $newP="assets/img/" . $filename;
    if (!is_dir(dirname($newPath))) {
        mkdir(dirname($newPath), 0777, true); // Tạo các thư mục một cách đệ qui nếu chúng không tồn tại
	   
	    // Tạo các thư mục một cách đệ qui nếu chúng không tồn tại
    }
    
    if (move_uploaded_file($pathTemp, $newPath)) {
        return $newP;
    } else {
        error_log("Không thể di chuyển tệp đã tải lên: $pathTemp tới $newPath");
        return '';
    }
}
function fixUrl($thumbnail, $rootPath = "../../") {
	if(stripos($thumbnail, 'http://') !== false || stripos($thumbnail, 'https://') !== false) {
	} else {
		$thumbnail = $rootPath.$thumbnail;
	}

	return $thumbnail;
}
?>