<?php
require_once ('config.php');

/**
 * Su dung voi cau lenh query: insert, update, delete -> ko tra ve ket qua.
 */
function execute($sql) {
	//Mo ket noi toi database
	$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
	mysqli_set_charset($conn, 'utf8');

	//Xu ly cau query
	mysqli_query($conn, $sql);

	//Dong ket noi database
	mysqli_close($conn);
}

function executeID($sql, $params) {
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
    mysqli_set_charset($conn, 'utf8');

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $types = "sssssss"; // 7 biến: tất cả đều là string
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);

        $last_id = mysqli_insert_id($conn); // Lấy ID vừa INSERT
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return $last_id;
    }

    mysqli_close($conn);
    return false;
}

function executePreparedStatement($sql, $params = []) {
    // Mở kết nối đến database
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
    mysqli_set_charset($conn, 'utf8');

    // Chuẩn bị câu lệnh SQL
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Lỗi chuẩn bị truy vấn: " . mysqli_error($conn));
    }

    // Nếu có tham số, bind vào câu lệnh SQL
    if (!empty($params)) {
        // Xây dựng kiểu dữ liệu cho bind_param (s = string, i = integer, d = double)
        $types = str_repeat('s', count($params)); // Mặc định là chuỗi
        $bind_names[] = &$types;
        for ($i = 0; $i < count($params); $i++) {
            $bind_names[] = &$params[$i];
        }
        call_user_func_array([$stmt, 'bind_param'], $bind_names);
    }

    // Thực thi câu lệnh
    $success = mysqli_stmt_execute($stmt);
    if (!$success) {
        die("Lỗi thực thi truy vấn: " . mysqli_stmt_error($stmt));
    }

    // Đóng câu lệnh và kết nối
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

/**
 * Su dung voi cau lenh query: select.
 */
function executeResult($sql) {
	//Mo ket noi toi database
	$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
	mysqli_set_charset($conn, 'utf8');

	// echo $sql;
	//Xu ly cau query
	$resultset = mysqli_query($conn, $sql);
	// var_dump($resultset);
	// die();
	$data = [];
	while (($row = mysqli_fetch_array($resultset, 1)) != null) {
		$data[] = $row;
	}
	/**
	 * TH: param2 = 1
	 * $row = [
	 * 		'id' => 1,
	 * 		'title' => '1 - Android Tivi Sony 4K 55 inch KD-55X8000H',
	 * 		'thumbnail' => '12321',
	 * 		...
	 * ];
	 *
	 * TH: param2 = 2
	 * $row = [1, '1 - Android Tivi Sony 4K 55 inch KD-55X8000H', '12321', ...];
	 */

	//Dong ket noi database
	mysqli_close($conn);

	return $data;
}
function excuteQuery($sql)
{
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
    
    // Kiểm tra kết nối
    if (!$conn) {
        die("Không thể kết nối đến cơ sở dữ liệu: " . mysqli_connect_error());
    }

    // Đặt bảng mã UTF-8
    if (!mysqli_set_charset($conn, "utf8")) {
        echo "Không thể thiết lập bảng mã: " . mysqli_error($conn);
    }

    // Thực thi truy vấn
    $result = mysqli_query($conn, $sql);

    // Kiểm tra lỗi thực thi truy vấn
    if (!$result) {
        echo "Không thể thực thi truy vấn: " . mysqli_error($conn);
    }

    // Đóng kết nối
    mysqli_close($conn);

    return $result;
}

function execute2($sql, $params = []) {
    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
    $stmt = mysqli_prepare($conn, $sql);
    if ($params) {
        mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
    }
    mysqli_stmt_execute($stmt);
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    return $affected_rows > 0;
}

function checkPrivilege($uri = false) {
	
    $uri = $uri != false ? $uri : $_SERVER['REQUEST_URI'];
	if(empty($_SESSION['current_user']['privileges'])){
		        return false;
		    }
	$privileges = $_SESSION['current_user']['privileges'];
    $privileges = implode("|", $privileges);
	// echo $privileges;
    preg_match('/' . $privileges . '/', $uri, $matches);
	//preg_match('/' . $privileges . '/', $uri, $matches);
    return !empty($matches);
}
// function checkPrivilege($uri = false) {
//     $uri = $uri != false ? $uri : $_SERVER['REQUEST_URI'];
//     if(empty($_SESSION['current_user']['privileges'])){
//         return false;
//     }
//     $privileges = $_SESSION['user']['privileges'];
//     $privileges = implode("|", $privileges);
//     preg_match('/index\.php$|' . $privileges . '/', $uri, $matches);
//     return !empty($matches);
// }
 // $privileges = array(
	// 	"category_index\.php$",
	// 	"product_index\.php$",
	// 	"feedback_index\.php$",
	//"user\/editor\.php\?id=\d+$",
	//"user_index\.php$",
// );