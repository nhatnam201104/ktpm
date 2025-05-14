<?php
session_start();
require_once('../../database/Database.php');
require_once('../../utils/App.php');
require_once('../../database/dbhelper.php');
class Chart extends App
{
     function displayChart()
     {
          $startDay = $_POST['start_day'];
          $endDay = $_POST['end_day'];
          $result = [];
          $sql_pullDb = "SELECT * FROM `order` WHERE status='2' AND created_date BETWEEN '$startDay' AND '$endDay' ORDER BY id ASC";
          $query0 = Database::getInstance()->execute($sql_pullDb);
          $index = 0;
          while ($row = $query0->fetch_assoc()) {
               $result[$index] = $row;
               $index++;
          }
          $this->renderJSON($result);
     }
     function displayChartCate()
     {
          $startDay = $_POST['start_day'];
          $endDay = $_POST['end_day'];
          $category_id=$_POST['selectCategory'];
          $response=[];
          $sql_pullDb = "SELECT *
               FROM `order`
               JOIN `order_item` ON `order`.id = `order_item`.order_id
               JOIN `product` ON `order_item`.product_id = `product`.id
               WHERE `order`.status = '2' 
                 AND `order`.created_date BETWEEN '$startDay' AND '$endDay' 
                 AND `product`.category_id = '$category_id'
                 ORDER BY `order_item`.id ASC";
          $query0 = Database::getInstance()->execute($sql_pullDb);
          $index = 0;
          while ($row = $query0->fetch_assoc()) {
               $product_id = $row['id'];
               $product_name = $row['name'];
               $item_qty = $row['qty'];
               $totalMoney= $row['total_price'];

               // Tạo hoặc cập nhật thông tin sản phẩm trong mảng response
               if (!isset($response[$product_id])) {
                    $response[$product_id] = [
                         'name' => $product_name,
                         'qty' => $item_qty,
                         'total_money' => $totalMoney
                    ];
               } else {
                    $response[$product_id]['qty'] += $item_qty;
                    $response[$product_id]['total_money'] += $totalMoney;
               }
          }
          $this->renderJSON($response);
     }
     function displayChartAllCate()
     {
          $startDay = $_POST['start_day'];
          $endDay = $_POST['end_day'];
          $response=[];
          $sql_pullDb = "SELECT *
               FROM `order`
               JOIN `order_item` ON `order`.id = `order_item`.order_id
               JOIN `product` ON `order_item`.product_id = `product`.id
               JOIN `category` ON `product`.category_id = `category`.id
               WHERE `order`.status = '2' 
                 AND `order`.created_date BETWEEN '$startDay' AND '$endDay'
                 ORDER BY `order_item`.id ASC";
               //   AND `product`.category_id = '$category_id'
          $query0 = Database::getInstance()->execute($sql_pullDb);
          $index = 0;
          while ($row = $query0->fetch_assoc()) {
               $product_id = $row['id'];
               $product_name = $row['name'];
               $item_qty = $row['qty'];
               $totalMoney= $row['total_price'];

               // Tạo hoặc cập nhật thông tin sản phẩm trong mảng response
               if (!isset($response[$product_id])) {
                    $response[$product_id] = [
                         'name' => $product_name,
                         'qty' => $item_qty,
                         'total_money' => $totalMoney
                    ];
               } else {
                    $response[$product_id]['qty'] += $item_qty;
                    $response[$product_id]['total_money'] += $totalMoney;
               }
          }
          $this->renderJSON($response);
     }
     function totalSum()
     {
          $startDay = $_POST['start_day'];
          $endDay = $_POST['end_day'];
          $result = [];
          $sql_pullDb = "SELECT * FROM `order` WHERE status='2' AND created_date BETWEEN '$startDay' AND '$endDay' ORDER BY id ASC";
          $query0 = Database::getInstance()->execute($sql_pullDb);
          $index = 0;
          $toatal = 0;
          while ($row = $query0->fetch_assoc()) {
               $result[$index] = $row;
               $toatal = $toatal + $result[$index]['total_money'];
               $index++;
          }
          $this->renderJSON($toatal);
     }
     function listProSold()
     {
          $out_put = "
          <tr>
                    <td class='font-weight-bold'>STT</td>
                    <td class='font-weight-bold'>Tên sản phẩm</td>
                    <td class='font-weight-bold'>Số lượng</td>
                </tr>";
          $startDay = $_POST['start_day'];
          $endDay = $_POST['end_day'];
          $response = [];

          $sql_pullDb = "SELECT o.*, oi.product_id, oi.qty as item_qty, p.name as product_name 
               FROM `order` o
               INNER JOIN `order_item` oi ON o.id = oi.order_id
               INNER JOIN `product` p ON oi.product_id = p.id
               WHERE o.status='2' AND o.created_date BETWEEN '$startDay' AND '$endDay'
               ORDER BY o.id ASC";

          $query = Database::getInstance()->execute($sql_pullDb);

          while ($row = $query->fetch_assoc()) {
               $order_id = $row['id'];
               $product_id = $row['product_id'];
               $product_name = $row['product_name'];
               $item_qty = $row['item_qty'];

               // Tạo hoặc cập nhật thông tin sản phẩm trong mảng response
               if (!isset($response[$product_id])) {
                    $response[$product_id] = [
                         'name' => $product_name,
                         'qty' => $item_qty
                    ];
               } else {
                    $response[$product_id]['qty'] += $item_qty;
               }
          }
          usort($response, function($a, $b) {
               return $b['qty'] <=> $a['qty']; // Sắp xếp giảm dần theo qty
           });

          $indexlist = 1;
          foreach ($response as $keylist => $value) {
               $out_put .= "
               <tr>
               <td>" . $indexlist . "</td>
               <td>" . $value['name'] . "</td>
               <td>" . $value['qty'] . "</td>
               </tr>
               ";
               $indexlist++;
          }
          echo $out_put;
     }
     function listProSoldCate()
     {
          $out_put='';
          $startDay = $_POST['start_day'];
          $endDay = $_POST['end_day'];
          $category_id=$_POST['selectCategory'];
          $response=[];
          $sql_pullDb = "SELECT *
               FROM `order`
               JOIN `order_item` ON `order`.id = `order_item`.order_id
               JOIN `product` ON `order_item`.product_id = `product`.id
               WHERE `order`.status = '2' 
                 AND `order`.created_date BETWEEN '$startDay' AND '$endDay' 
                 AND `product`.category_id = '$category_id'
                 ORDER BY `order_item`.id ASC";
          $query0 = Database::getInstance()->execute($sql_pullDb);
          $index = 0;
          while ($row = $query0->fetch_assoc()) {
               $product_id = $row['id'];
               $product_name = $row['name'];
               $item_qty = $row['qty'];
               $totalMoney= $row['total_money'];

               // Tạo hoặc cập nhật thông tin sản phẩm trong mảng response
               if (!isset($response[$product_id])) {
                    $response[$product_id] = [
                         'name' => $product_name,
                         'qty' => $item_qty,
                         'total_money' => $totalMoney
                    ];
               } else {
                    $response[$product_id]['qty'] += $item_qty;
                    $response[$product_id]['total_money'] += $totalMoney;
               }
          }
          $out_put .= "
          <tr>
                    <td class='font-weight-bold'>STT</td>
                    <td class='font-weight-bold'>Tên sản phẩm</td>
                    <td class='font-weight-bold'>Số lượng</td>
                </tr>";

          usort($response, function($a, $b) {
               return $b['qty'] <=> $a['qty']; // Sắp xếp giảm dần theo qty
           });

          $indexlist = 1;
          foreach ($response as $keylist => $value) {
               $out_put .= "
               <tr>
               <td>" . $indexlist . "</td>
               <td>" . $value['name'] . "</td>
               <td>" . $value['qty'] . "</td>
               </tr>
               ";
               $indexlist++;
          }
          echo $out_put;
     }
     function listProSoldAllCate()
     {
          $out_put='';
          $startDay = $_POST['start_day'];
          $endDay = $_POST['end_day'];
          $response=[];
          $sql_pullDb = "SELECT *
               FROM `order`
               JOIN `order_item` ON `order`.id = `order_item`.order_id
               JOIN `product` ON `order_item`.product_id = `product`.id
               WHERE `order`.status = '2' 
                 AND `order`.created_date BETWEEN '$startDay' AND '$endDay' 
                 ORDER BY `order_item`.id ASC";
          $query0 = Database::getInstance()->execute($sql_pullDb);
          $index = 0;
          while ($row = $query0->fetch_assoc()) {
               $product_id = $row['id'];
               $product_name = $row['name'];
               $item_qty = $row['qty'];
               $totalMoney= $row['total_money'];

               // Tạo hoặc cập nhật thông tin sản phẩm trong mảng response
               if (!isset($response[$product_id])) {
                    $response[$product_id] = [
                         'name' => $product_name,
                         'qty' => $item_qty,
                         'total_money' => $totalMoney
                    ];
               } else {
                    $response[$product_id]['qty'] += $item_qty;
                    $response[$product_id]['total_money'] += $totalMoney;
               }
          }
          $out_put .= "
          <tr>
                    <td class='font-weight-bold'>STT</td>
                    <td class='font-weight-bold'>Tên sản phẩm</td>
                    <td class='font-weight-bold'>Số lượng</td>
                </tr>";

          usort($response, function($a, $b) {
               return $b['qty'] <=> $a['qty']; // Sắp xếp giảm dần theo qty
           });

          $indexlist = 1;
          foreach ($response as $keylist => $value) {
               $out_put .= "
               <tr>
               <td>" . $indexlist . "</td>
               <td>" . $value['name'] . "</td>
               <td>" . $value['qty'] . "</td>
               </tr>
               ";
               $indexlist++;
          }
          echo $out_put;
     }
}
$chart = new Chart();
