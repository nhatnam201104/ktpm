<?php
// những dữ liệu lấy từ file config
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);
    if($conn->connect_error){
        die("Kết nối thất bại". $conn->connect_error);
    }
    // thiết lập bộ ký tự
    mysqli_set_charset($conn,"utf8");

?>