<?php 
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh');
 // load models
 require "../database2/config.php";
 require "../database2/connectDB.php";
 require "../database2/bootstrap.php";
// require "../vendor/autoload.php";

//router
$c = $_GET["c"] ?? "home";
$a = $_GET["a"] ?? "index";
$controllerName = ucfirst($c). "Controller";//StudentController
require "controller/" . $controllerName . ".php";
$controller = new $controllerName();
$controller->$a();
// $controller->list();
?>