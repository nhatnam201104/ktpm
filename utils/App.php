<?php
class App
{
     function __construct()
     {
          $url = trim($_SERVER['REQUEST_URI'], '/');
          $url = filter_var($url, FILTER_SANITIZE_URL);
          $url = explode('/', $url);


          $action = $url[4];
          $this->{$action}();
     }

     protected function renderJSON($data){
          header("Content-Type: application/json");
          echo json_encode($data, JSON_UNESCAPED_UNICODE);
     }
     protected function checkPrivilege1($uri = false)
{

	$uri = $uri != false ? $uri : $_SERVER['REQUEST_URI'];
	if (empty($_SESSION['current_user']['privileges'])) {
		return false;
	}
	// $privileges = array(
	// 	"category_index\.php$",
	// 	"product_index\.php$",
	// 	"feedback_index\.php$"
	// );
	$privileges = $_SESSION['current_user']['privileges'];
	$privileges = implode("|", $privileges);
	preg_match('/dashboard\.php$|' . $privileges . '/', $uri, $matches);
	return !empty($matches);
}
}
