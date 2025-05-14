<?php
session_start();
require_once('../../database/Database.php');
require_once('../../utils/App.php');
require_once('../../database/dbhelper.php');



class Supplier extends App
{
     function __construct()
     {
          parent::__construct();
     }

     public function getList()
     {
          $out_put = "";
          $query = "SELECT * from supplier order by id desc";
          $sql_select = Database::getInstance()->execute($query);
          // $query1 = "SELECT * from supplier_category order by id asc";
          // $sql_select1 = Database::getInstance()->execute($query1);
          $out_put .= "
    <div>
      <table width=100% class='table table-striped table-hover table-supplier'>
        <tr>
            <td class='font-weight-bold'>STT</td>
            <td class='font-weight-bold'>ID Nhà cung cấp</td>
            <td class='font-weight-bold'>Tên nhà cung cấp</td>
            <td class='font-weight-bold'>Địa chỉ</td>
            <td class='font-weight-bold'>Danh mục sản phẩm cung cấp</td>
            <td class='font-weight-bold'>Quản lý</td>
        </tr>
  ";
          if (mysqli_num_rows($sql_select) > 0) {
               $i = 0;
               while ($row = mysqli_fetch_array($sql_select)) {
                    $i++;
                    $out_put .= "
                         <tr>
                              <td>" . $i . "</td>
                              <td>" . $row['id'] . "</td>
                              <td>" . $row['name'] . "</td>
                              <td>" . $row['address'] . "</td>
                              <td>
                              ";
                    $out_put .= "<button data-id_option='" . $row['id'] . "' data-toggle='modal' data-target='#modal-option' class='option-data btn btn-success' name='edit'>Thêm</button> ";

                    $out_put .= "
                    <button data-id_view='" . $row['id'] . "' data-toggle='modal' data-target='#modal-view' class='view-data btn btn-primary' name='edit'>Xem</button></td>
                    </td>
                   
                    <td>";

                    $out_put .= "<button data-id_sua='" . $row['id'] . "' data-toggle='modal' data-target='#modal-edit-supplier' class='edit-supplier-data btn btn-warning'  id='" . $row['id'] . "'>Sửa</button> ";


                    if ($row['display'] == 1) {
                         $out_put .= "<button data-id_xoa='" . $row['id'] . "' data-status=0 class='del-supplier-data btn btn-danger' name='delete_data'>Khóa</button>";
                    } else {
                         $out_put .= "<button data-id_xoa='" . $row['id'] . "' data-status=1 class='del-supplier-data btn btn-success' name='delete_data'>Xác Nhận</button>";
                    }


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
     public function addcatesup()
     {
          $supplier_id = $_POST['supplier_id'];
          $category_id = $_POST['category_id'];
          $query = "select * from supplier_category where supplier_id=$supplier_id and category_id=$category_id";
          $result = Database::getInstance()->execute($query);
          if (mysqli_num_rows($result) == 0) {
               $query = "INSERT into supplier_category(supplier_id,category_id) value('$supplier_id','$category_id')";
               $result = Database::getInstance()->execute($query);
          }
     }

     public function add()
     {
          $name_supplier = $_POST['name_supplier'];
          $address_supplier = $_POST['address_supplier'];
          $category_id = $_POST['category_id'];
          $query = "INSERT into supplier(name,address) value('$name_supplier','$address_supplier')";
          $result = Database::getInstance()->execute($query);
          $query0 = "SELECT * from supplier order by id desc";
          $result1 = Database::getInstance()->execute($query0);
          $row = mysqli_fetch_array($result1);
          $this->renderJSON($row);
          $query1 = "INSERT into supplier_category(supplier_id,category_id) value('" . $row['id'] . "','$category_id')";
          $result2 = Database::getInstance()->execute($query1);
     }

     public function delete()
     {
          $id = $_POST['id'];
          $status = $_POST['status'];
          $query = "UPDATE supplier set display=$status where id=$id";
          $result = Database::getInstance()->execute($query);
     }
     public function viewCategorySupply()
     {
          $id = $_POST['id_view'];
          $sql = "SELECT * from supplier_category where supplier_id=$id";
          $query = Database::getInstance()->execute($sql);
          $out_put = "";
          $out_put .= "
    <div>
    <table width=100% class='table table-striped table-hover table-supplier'>
        <tr>
            <td>STT</td>
            <td>Danh mục sản phẩm</td>
        </tr>
  ";
          if (mysqli_num_rows($query) > 0) {
               $i = 0;

               while ($row = mysqli_fetch_array($query)) {
                    $sql_product = "SELECT * from category where id='" . $row['category_id'] . "'";
                    $query_product = Database::getInstance()->execute($sql_product);
                    $category = $query_product->fetch_assoc();
                    $i++;
                    $out_put .= "
                         <tr>
                              <td>" . $i . "</td>
                              <td>" . $row['category_id'] . "-" . $category['name'] . "</td>
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

     public function update()
     {
          $id = $_POST['id'];
          $name_supplier = $_POST['name_supplier'];
          $address_supplier = $_POST['address_supplier'];
          $sql_editcode = "UPDATE supplier set name='" . $name_supplier . "',address= '" . $address_supplier . "' where id=$id";
          $result = Database::getInstance()->execute($sql_editcode);
     }
     public function getSupplierById()
     {
          $id = $_POST['id'];
          $sql_ajax = "SELECT * from supplier where id=$id limit 1";
          $result = Database::getInstance()->execute($sql_ajax);
          $row = mysqli_fetch_assoc($result);
          $this->renderJSON($row);
     }
     public function displayCategoryInSelect()
     {
          $sql_select = "SELECT * from category where deleted=0 order by id asc";
          $query = Database::getInstance()->execute($sql_select);
          $result = [];
          $index = 0;
          while ($row = $query->fetch_assoc()) {
               $result[$index++] = $row;
          }
          $this->renderJSON($result);
     }
}
$supplier = new Supplier();
