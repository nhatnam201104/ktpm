<?php
require_once('config.php');

class Database
{
     private static $instance;
     private $connection;

     public static function getInstance()
     {
          if (!self::$instance) {
               self::$instance = new Database();
          }
          return self::$instance;
     }

     public function getConnection()
     {
          if (!$this->connection) {
               $this->connection = new mysqli(HOST, USERNAME, PASSWORD, DATABASE);

               if ($this->connection->connect_errno) {
                    echo "Failed to connect to MySQL: " . $this->connection->connect_error;
                    exit();
               }
          }
          return $this->connection;
     }

     public function execute($query)
     {
          return mysqli_query($this->getConnection(), $query);
     }
     public function checkPrivilege2($uri=false) {
	
          $uri = $uri != false ? $uri : $_SERVER['REQUEST_URI'];
           if(empty($_SESSION['current_user']['privileges'])){
                        return false;
                    }
          // $privileges = array(
           // 	"category_index\.php$",
           // 	"product_index\.php$",
           // 	"feedback_index\.php$"
           // );
           $privileges = $_SESSION['current_user']['privileges'];
          $privileges = implode("|", $privileges);
          preg_match('/index\.php$|' . $privileges . '/', $uri, $matches);
          return !empty($matches);
      }
}
