

<?php
session_start();
require_once('../../database/Database.php');
require_once('../../utils/App.php');
require_once('../../database/dbhelper.php');



class Enter_coupon extends App
{
     function __construct()
     {
          parent::__construct();
     }

     public function getList()
     {
          $out_put = "";
          $query = "SELECT * from enter_coupon order by id desc";
          $sql_select = Database::getInstance()->execute($query);
          $out_put .= "
    <div>
    <table width=100% class='table table-striped table-hover table-discount '>
        <tr class='thead-light'>
            <td class='font-weight-bold'>STT</td>
            <td class='font-weight-bold'>Nhà cung cấp</td>
            <td class='font-weight-bold'>Nhân viên</td>
            <td class='font-weight-bold'>Ngày nhập</td>
            <td class='font-weight-bold'>Chi tiết</td>
            <td class='font-weight-bold'>Quản lý</td>
        </tr>
  ";
          if (mysqli_num_rows($sql_select) > 0) {
               $i = 0;

               while ($row = mysqli_fetch_array($sql_select)) {
                    $sql_suppliername = "SELECT * from supplier where id='" . $row['supplier_id'] . "'";
                    $query_suppliername = Database::getInstance()->execute($sql_suppliername);
                    $supplier = $query_suppliername->fetch_assoc();
                    $sql_staff = "SELECT * from user where id='" . $row['staff_id'] . "'";
                    $query_staffname = Database::getInstance()->execute($sql_staff);
                    $staff = $query_staffname->fetch_assoc();
                    $i++;
                    $out_put .= "
             <tr>
                 <td>" . $i . "</td>
                 <td>" . $row['supplier_id'] . "-" . $supplier['name'] . "</td>
                 <td>" . $row['staff_id'] . "-" . $staff['name'] . "</td>
                 <td>" . $row['enter_day'] . "</td>
                 <td><button data-id_view='" . $row['id'] . "' data-toggle='modal' data-target='#modal-view' class='view-data btn btn-primary' name='edit'>Xem</button></td>
                 <td>";



                 
                 
                 if ($row['status'] == 0) {
                      $out_put .= "<button class='btn btn-success'>Đã xác nhận</button>";
                      // echo '<button onclick="statusUser(' . $row['id'] . ',0)" class="btn btn-success btn-sm shadow-none">Đã Kích Hoạt</button>';
                    } else {
                              $out_put .= "<button data-id_delete='" . $row['id'] . "' data-status=0 class='del-import-data btn btn-danger' name='delete_import'>Xóa</button> ";
                              $out_put .= "<button data-id_xoa='" . $row['id'] . "' data-status=0 class='del-entercoupon-data btn btn-danger' name='delete_data'>Chờ Xác Nhận</button>";
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
          $query = "INSERT into supplier_category(supplier_id,category_id) value('$supplier_id','$category_id')";
          $result = Database::getInstance()->execute($query);
     }

     public function addEnterCoupon()
     {
          $supplier_id = $_POST['supplier_id'];
          $user_id =$_SESSION['current_user']['id'];
          // $_POST['user_id'];
          $createdAt = $_POST['created_at'];
          $query1 = "INSERT into enter_coupon(supplier_id,staff_id,enter_day) value('$supplier_id','$user_id','$createdAt')";
          $result = Database::getInstance()->execute($query1);
     }
     public function add()
     {
          $product_id = $_POST['product_id'];
          $product_qty = $_POST['product_qty'];
          $sql_entercoupon = "SELECT * from enter_coupon  order by id desc";
          $query = Database::getInstance()->execute($sql_entercoupon);
          $row = $query->fetch_assoc();

          $sql_entercoupon = "SELECT * FROM `entry_details` where entercoupon_id=$row[id] and product_id=$_POST[product_id]";
          $query = Database::getInstance()->execute($sql_entercoupon);
          if(mysqli_num_rows($query)>0)
          {
               $sql_entercoupon = "UPDATE `entry_details` 
               SET `product_qty` = $_POST[product_qty], 
                   `p_inventory` = $_POST[product_qty], 
                   `enter_price` = $_POST[enter_price], 
                   `profit_margin` = $_POST[profit_margin] 
               WHERE entercoupon_id = $row[id] AND product_id = $_POST[product_id]";
               $query = Database::getInstance()->execute($sql_entercoupon);

          } else {
               $sql_insert = "INSERT into entry_details(entercoupon_id,supplier_id,product_id,product_qty,p_inventory,enter_price,profit_margin) values('" . $row['id'] . "','" . $row['supplier_id'] . "','$product_id','$product_qty','$product_qty','$_POST[enter_price]','$_POST[profit_margin]')";
               $result = Database::getInstance()->execute($sql_insert);
               $sql_selectQtyProduct="SELECT * from product where id=$product_id";
               $query_selectQty=Database::getInstance()->execute($sql_selectQtyProduct);
               $product_qty_root=$query_selectQty->fetch_assoc();
               $totalQty=$product_qty+$product_qty_root['inventory_qty'];
               $query_update_qty=Database::getInstance()->execute("UPDATE product set inventory_qty='$totalQty' where id=$product_id");
          }
     }


     public function delete()
     {
          $id = $_POST['id_xoa'];
          $query = "UPDATE enter_coupon set status=$_POST[status] where id=$id";
          $result = Database::getInstance()->execute($query);
     }

     public function delete_import()
     {
          $id = $_POST['id_xoa'];
          $query = "DELETE from entry_details where entercoupon_id=$id";
          $result = Database::getInstance()->execute($query);
          $query = "DELETE from enter_coupon where id=$id";
          $result = Database::getInstance()->execute($query);
     }


     public function update()
     {

          $id = $_POST['id'];
          $staff_id = $_POST['staff'];
          $sql_editcode_entercoupon = "UPDATE enter_coupon set staff_id='" . $staff_id . "' where id=$id";
          $result = Database::getInstance()->execute($sql_editcode_entercoupon);
     }
     public function getSupplierById()
     {
          $id = $_POST['id'];
          $sql_ajax = "SELECT * from supplier where id=$id limit 1";
          $result = Database::getInstance()->execute($sql_ajax);
          $row = mysqli_fetch_assoc($result);
          $this->renderJSON($row);
     }
     public function displaySupplierInSelect()
     {
          $sql_select = "SELECT * from supplier where display=0 order by id asc";
          $query = Database::getInstance()->execute($sql_select);
          $result = [];
          $index = 0;
          while ($row = $query->fetch_assoc()) {
               $result[$index++] = $row;
          }
          $this->renderJSON($result);
     }
     public function displayUserInSelect()
     {
          $sql_select = "SELECT * from user where role_id=3 order by id asc";
          $query = Database::getInstance()->execute($sql_select);
          $result = [];
          $index = 0;
          while ($row = $query->fetch_assoc()) {
               $result[$index++] = $row;
          }
          $this->renderJSON($result);
     }
     public function getListProduct()
     {
          $supplierg_id = $_POST['supplier_id'];
          $sql_select = "SELECT p.* FROM category c INNER JOIN supplier_category s ON c.id=s.category_id INNER JOIN product p ON p.category_id=c.id where s.supplier_id=$supplierg_id";
          $query = Database::getInstance()->execute($sql_select);
          $result = [];
          $index = 0;
          while ($row = $query->fetch_assoc()) {
               $result[$index++] = $row;
          }
          $this->renderJSON($result);
     }
     // Chi tiết phiếu nhập
     public function viewEntryDetails()
     {
          $id = $_POST['id_view'];
          $sql = "SELECT * from entry_details where entercoupon_id=$id";
          $query = Database::getInstance()->execute($sql);
          $out_put = "";
          $out_put .= "
    <div>
    <table width=100% class='table table-striped table-hover table-discount'>
        <tr>
            <td class='font-weight-bold'>STT</td>
            <td class='font-weight-bold'>Sản phẩm</td>
            <td class='font-weight-bold'>Số lượng</td>
            <td class='font-weight-bold'>Giá Nhập</td>
            <td class='font-weight-bold'>Tỉ suất lợi nhuận</td>

        </tr>
  ";
          if (mysqli_num_rows($query) > 0) {
               $i = 0;

               while ($row = mysqli_fetch_array($query)) {
                    $sql_product = "SELECT * from product where id='" . $row['product_id'] . "'";
                    $query_product = Database::getInstance()->execute($sql_product);
                    $product = $query_product->fetch_assoc();
                    $i++;
                    $out_put .= "
                         <tr>
                              <td>" . $i . "</td>
                              <td>" . $row['product_id'] . "-" . $product['name'] . "</td>
                              <td>" . $row['product_qty'] . "</td>
                              <td>" . $row['enter_price'] . "</td>
                              <td>" . $row['profit_margin'] . "</td>

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
}
$entercoupon = new Enter_coupon();
