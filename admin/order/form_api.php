<?php
session_start();
require_once('../../utils/utility2.php');
require_once('../../database/dbhelper.php');

$user = getUserToken();
if ($user == null) {
	die();
}

if (!empty($_POST)) {
	$action = getPost('action');

	switch ($action) {
		case 'update_status':
			updateStatus();
			break;
		case 'checkCus':
			checkCus();
			break;
		case 'getListProduct':
			getListProduct();
			break;
		case 'priceProduct':
			priceProduct();
			break;
		case  'addInvoice':
			addInvoice();
			break;
	}
}

function updateStatus()
{
	$id = getPost('id');
	$status = getPost('status');
	$sql = "UPDATE `Order` SET status = $status WHERE id = $id";
	execute($sql);
	if ($status == "3") {
		$select = "SELECT * FROM `order_item` WHERE order_id=$id";
		$result = executeResult($select);
		foreach ($result as $row) {
			$sql1 = "UPDATE entry_details 
			SET p_inventory = p_inventory + $row[qty] 
			WHERE product_id = $row[product_id] AND entercoupon_id = $row[batch_id]";
			execute($sql1);
		}
	}
}

function checkCus()
{
	$phone = getPost('phone_customer');
	$sql = "SELECT * FROM `user` WHERE mobile = '$phone' AND role_id = 5";
	$result = executeResult($sql);

	if (count($result) > 0) {
		echo json_encode(["success" => true]);
	} else {
		echo json_encode(["success" => false, "message" => "Số điện thoại không hợp lệ!"]);
	}
}

function getListProduct()
{
	$sql_select = "SELECT pr.*
	FROM product pr
	WHERE pr.id IN (
		SELECT product_id
		FROM entry_details ed
		INNER JOIN enter_coupon ec ON ec.id = ed.entercoupon_id
		WHERE ec.status = 0 
		GROUP BY product_id
		HAVING SUM(p_inventory) > 0
	) 
	AND pr.deleted = 0;";
	$query = executeResult($sql_select);
	$result = [];
	foreach ($query as $row) {
		$result[] = $row;
	}
	echo json_encode($result);
}


function priceProduct()
{
	$product_id = getPost('product_id');
	$product_qty = getPost('product_qty');
	$total_cost = 0;
	$remaining = $product_qty;

	$sql_select = "SELECT 
                ed.entercoupon_id AS batch_id,
                ed.p_inventory,
                (ed.enter_price * (1 + (ed.profit_margin / 100))) - 
                ((ed.enter_price * (1 + (ed.profit_margin / 100))) * COALESCE(d.discount_percentage, 0) / 100) AS price
            FROM enter_coupon ec
            JOIN entry_details ed ON ec.id = ed.entercoupon_id
            JOIN product pr ON ed.product_id = pr.id
            LEFT JOIN discount d ON d.id = pr.discount_id
            WHERE ec.status = 0 AND ed.p_inventory > 0 AND ed.product_id = $product_id
            ORDER BY ec.enter_day ASC";
	$query = executeResult($sql_select);
	$result = [];
	foreach ($query as $row) {
		$result[] = $row;
	}
	$total_qty_data = array_sum(array_column($result, 'p_inventory'));
	if($product_qty>$total_qty_data){
		echo json_encode(["success" => false, "message" => "Không đủ số lượng: $total_qty_data"]);
		exit;
	}
	foreach ($result as $row) {
		if ($remaining <= 0) break;
		$take = min($row['p_inventory'], $remaining);
		$batch_cost = $take * $row['price'];
		$total_cost += $batch_cost;

		$remaining -= $take;
	}

	echo json_encode(["success" => true, "message" => $total_cost]);
}


function addInvoice()
{
    $phone_customer = $_POST['phone_customer'];
    $listOrder = json_decode($_POST['listOrder'], true);
    $order_list = [];

    if (!is_array($listOrder)) {
        echo json_encode(["success" => false, "message" => "Không có danh sách hóa đơn"]);
        return;
    }

    $total_cost = 0;

    foreach ($listOrder as $order) {
        $product_id = $order['product_id'];
        $quantity = $order['quantity'];
        $price = $order['price'];
        $priceTest = 0;
        $remaining = $quantity;

        // 🔹 Lấy danh sách lô hàng theo FIFO
        $sql_select = "SELECT 
                ed.entercoupon_id AS batch_id,
                ed.p_inventory,
                (ed.enter_price * (1 + (ed.profit_margin / 100))) - 
                ((ed.enter_price * (1 + (ed.profit_margin / 100))) * COALESCE(d.discount_percentage, 0) / 100) AS price
            FROM enter_coupon ec
            JOIN entry_details ed ON ec.id = ed.entercoupon_id
            JOIN product pr ON ed.product_id = pr.id
            LEFT JOIN discount d ON d.id = pr.discount_id
            WHERE ec.status = 0 AND ed.p_inventory > 0 AND ed.product_id = $product_id
            ORDER BY ec.enter_day ASC";

        $query = executeResult($sql_select);
        $result = [];
        foreach ($query as $row) {
            $result[] = $row;
        }

        $total_qty_data = array_sum(array_column($result, 'p_inventory'));

        if ($quantity > $total_qty_data) {
            echo json_encode(["success" => false, "message" => "Sản phẩm có id $product_id không đủ số lượng trong kho"]);
            exit;
        }

        // 🔹 Chia số lượng theo từng lô hàng
        foreach ($result as $row) {
            if ($remaining <= 0) break;
            $take = min($row['p_inventory'], $remaining);
			$unit_price = $row['price'];
            $batch_cost = $take * $row['price'];
            $priceTest += $batch_cost;
            $remaining -= $take;
			$batch_id = $row['batch_id'];


            $order_list[] = [
                'product_id' => $product_id,
                'quantity' => $take,
				'unit_price' => $unit_price,
                'price' => $batch_cost,
				'batch_id' => $batch_id
            ];

            $sql_update = "UPDATE entry_details SET p_inventory = p_inventory - $take 
                           WHERE entercoupon_id = $batch_id AND product_id = $product_id";
            execute($sql_update);
        }

        // 🔹 Kiểm tra sai số giá
        if (round($priceTest, 2) != round($price, 2)) {
            echo json_encode(["success" => false, "message" => "Có lỗi xảy ra!"]);
            exit;
        }

        // 🔹 Cập nhật tổng tiền
        $total_cost += $priceTest;
    }

	$created_date = date("Y-m-d H:i:s");
	$delivered_date = date("Y-m-d H:i:s", strtotime("+3 days"));
    // Thêm đơn hàng
    $sql1 = "INSERT INTO `order`(`created_date`, `status`, `user_id`, `delivered_date`, `cus_fullname`, `cus_mobile`, `cus_address`, `total_money`) 
         VALUES (?, 0, (SELECT id FROM user WHERE mobile = ?), ?, (SELECT name FROM user WHERE mobile = ?), ?, (SELECT address FROM user WHERE mobile = ?), ?)";

	$params = [$created_date, $phone_customer, $delivered_date, $phone_customer, $phone_customer, $phone_customer, $total_cost];
    $id =  executeID($sql1, $params);

    if (!$id) {
        echo json_encode(["success" => false, "message" => "Lỗi khi thêm hóa đơn!"]);
        exit;
    }

    // Thêm từng sản phẩm vào đơn hàng
    $sql = "INSERT INTO `order_item`(`order_id`, `product_id`, `qty`, `unit_price`, `total_price`, `batch_id`) VALUES ";
	$values = [];
	$params = [];

	foreach ($order_list as $item) {
		$values[] = "(?, ?, ?, ?, ?, ?)";
		array_push($params, $id, $item['product_id'], $item['quantity'], $item['unit_price'], $item['price'], $item['batch_id']);
	}
	$sql .= implode(",", $values); // Nối các phần VALUES vào câu lệnh SQL
	// Gọi hàm thực thi với câu SQL và danh sách tham số
	executePreparedStatement($sql, $params);

    echo json_encode(["success" => true, "message" => "Thêm hóa đơn thành công"]);
}