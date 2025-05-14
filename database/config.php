<?php
define('HOST','localhost');
define('DATABASE','ktpm');
define('USERNAME','root');
define('PASSWORD','');
define('PRIVATE_KEY','AFAWCA251!2314()');
$con = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
if (mysqli_connect_errno()){
    echo "Connection Fail: ".mysqli_connect_errno();exit;
}
?>