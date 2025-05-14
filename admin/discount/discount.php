<?php
session_start();
require_once('../../database/Database.php');
require_once('../../utils/App.php');
require_once('../../database/dbhelper.php');
class Discount extends App
{
     function __construct()
     {
          parent::__construct();
     }
     public function getList()
     {
         $out_put = "";
         $query = "SELECT * from discount where display=0 and id!=0 order by id desc";
         $sql_select = Database::getInstance()->execute($query);
         $out_put .= "
         <div>
           <table width=100% class='table table-striped table-hover table-discount'>
             <tr>
                 <td class='font-weight-bold'>Mã giảm giá</td>
                 <td class='font-weight-bold'>Tên mã giảm giá</td>
                 <td class='font-weight-bold'>% giảm giá</td>
                 <td class='font-weight-bold'>Ngày bắt đầu</td>
                 <td class='font-weight-bold'>Ngày kết thúc</td>
                 <td class='font-weight-bold'>Quản lý</td>
             </tr>
         ";
         if (mysqli_num_rows($sql_select) > 0) {
             while ($row = mysqli_fetch_array($sql_select)) {
                 $out_put .= "
                 <tr>
                     <td>" . $row['id'] . "</td>
                     <td>" . $row['name'] . "</td>
                     <td>" . $row['discount_percentage'] . "</td>
                     <td>" . $row['start_day'] . "</td>
                     <td>" . $row['finish_date'] . "</td>
                     <td>
                 ";
                 
                     $out_put .= "<button data-id_xoa='" . $row['id'] . "' class='del_data btn btn-danger' name='delete_data'>Xóa</button> ";
                 
                 
                     $out_put .= "<button data-id_sua='" . $row['id'] . "' data-toggle='modal' data-target='#modal-edit' class='edit-data btn btn-warning' name='edit' id='" . $row['id'] . "'>Sửa</button>";
                 
     
                 $out_put .= "</td>
                 </tr>
                 ";
             }
         } else {
             $out_put .= "
             <tr>
                 <td>Dữ liệu chưa được tải</td>
             </tr>
             ";
         }
         $out_put .= "</table></div>";
         echo $out_put;
     }
     

     public function add() {
        // Lấy ID lớn nhất
        $maxIdQuery = "SELECT MAX(id) as max_id FROM discount";
        $result = Database::getInstance()->execute($maxIdQuery);
        $row = mysqli_fetch_assoc($result);
        $newId = $row['max_id'] + 1;
    
        // Thêm dữ liệu mới với ID tự tăng
        $name = $_POST['name_code'];
        $percentcode = $_POST['percent_code']; 
        $firstday = $_POST['start_day'];
        $finishday = $_POST['end_day'];
        
        $query = "INSERT into discount(id, name, discount_percentage, start_day, finish_date) 
                  VALUES ($newId, '$name', '$percentcode', '$firstday', '$finishday')";
                
        $result = Database::getInstance()->execute($query);
    }
     public function delete()
     {
          $id = $_POST['id'];
          $query = "UPDATE discount set display=1 where id=$id";
          $result = Database::getInstance()->execute($query);
     }

     public function update()
     {
          $id = $_POST['id_edit'];
          $name = $_POST['name_code'];
          $percentcode = $_POST['percent_code'];
          $firstday = $_POST['first_day'];
          $finishday = $_POST['finish_day'];
          $sql_editcode = "UPDATE discount set name='" . $name . "',discount_percentage= '" . $percentcode . "', start_day='" . $firstday . "', finish_date='" . $finishday . "' where id=$id";
          $result = Database::getInstance()->execute($sql_editcode);
     }
     public function getDiscountById()
     {
          $id = $_POST['id_sua'];
          $sql_ajax = "SELECT * from discount where id=$id limit 1";
          $result = Database::getInstance()->execute($sql_ajax);
          $row = mysqli_fetch_assoc($result);
          $this->renderJSON($row);
     }
}
$discount = new Discount();
