<?php
class OrderRepository extends BaseRepository
{
	// protected function fetchAll($condition = null, $sort = null)
	// {
	// 	global $conn;
	// 	$orders = array();
	// 	$sql = "SELECT * FROM `order`";
	// 	if ($condition) {
	// 		$sql .= " WHERE  $condition";
	// 	}

	// 	if ($sort) {
	// 		$sql .= " $sort";
	// 	}

	// 	$result = $conn->query($sql);

	// 	if ($result->num_rows > 0) {
	// 		while ($row = $result->fetch_assoc()) {
	// 			$order = new Order($row["id"], $row["created_date"], $row["status"], $row["user_id"], $row["payment_method"], $row["shipping_fee"], $row["cus_fullname"], $row["cus_mobile"], $row["cus_address"], $row["delivered_date"], $row["total_money"]);
	// 			$orders[] = $order;
	// 		}
	// 	}

	// 	return $orders;
	// }

	protected function fetchAll($condition = null, $sort = null)
	{
		global $conn;
		$orders = array();
		$sql = "SELECT * FROM `order`";
		if ($condition) {
			$sql .= " WHERE $condition";
		}

		if ($sort) {
			$sql .= " $sort";
		}

		$result = $conn->query($sql);

		if (!$result) {
			die("Query failed: " . mysqli_error($conn));
		}

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				$order = new Order(
					$row["id"],
					$row["created_date"],
					$row["status"],
					$row["user_id"],
					$row["payment_method"],
					$row["delivered_date"],
					$row["cus_fullname"],
					$row["cus_mobile"],
					$row["cus_address"],
					$row["total_money"]
				);
				$orders[] = $order;
			}
		}

		return $orders;
	}

	

	function getAll()
	{
		return $this->fetchAll();
	}

	function getByCustomerId($customer_id)
	{
		global $conn;
		$condition = "user_id = $customer_id";
		$sort = "ORDER BY id DESC";
		return $this->fetchAll($condition, $sort);
	}

	function find($id)
	{
		global $conn;
		$condition = "id = $id";
		$orders = $this->fetchAll($condition);
		$order = current($orders);
		return $order;
	}

	function save($data)
	{
		global $conn;
		$created_date = $data["created_date"];
		$status = $data["status"];
		$user_id = $data["user_id"];
		$cus_mobile = $data["cus_mobile"];
		$cus_fullname = $data["cus_fullname"];
		$cus_address = $data["cus_address"];
		$payment_method = $data["payment_method"];
		$delivered_date = $data["delivered_date"];


		$sql = "INSERT INTO `order` (created_date, status, user_id, payment_method, delivered_date, cus_fullname, cus_mobile, cus_address) VALUES ('$created_date', $status, $user_id, '$payment_method', '$delivered_date', '$cus_fullname', '$cus_mobile', '$cus_address')";
		if ($conn->query($sql) === TRUE) {
			$last_id = $conn->insert_id; //chỉ cho auto increment
			return $last_id;
		}
		echo "Error: " . $sql . PHP_EOL . $conn->error;
		return false;
	}
	function saveMoney($order_id, $total_money)
	{
		global $conn;
		$sql = "UPDATE `order` SET total_money = '$total_money' WHERE id = $order_id";
		if ($conn->query($sql) === TRUE) {
			return true;
		}
		echo "Error: " . $sql . PHP_EOL . $conn->error;
		return false;
	}
	
	
	function update($order)
	{
		global $conn;
		$id = $order->getId();
		$created_date = $order->getCreatedDate();
		$status = $order->getStatusId();
		$staff_id = $order->getStaffId();
		$customer_id = $order->getCustomerId();
		$cus_mobile = $order->getShippingMobile();
		$cus_fullname = $order->getCustomerFullname();
		$payment_method = $order->getPaymentMethod();
		$cus_address = $order->getShippingHousenumberStreet();
		$shipping_fee = $order->getShippingFee();
		$delivered_date = $order->getDeliveredDate();
		$total_money = $order->getTotalMoney();
		$sql = "UPDATE `order` SET 
			created_date='$created_date', 
			status=$status, 
			staff_id=$staff_id, 
			customer_id=$customer_id,  
			cus_fullname='$cus_fullname', 
			cus_mobile='$cus_mobile', 
			payment_method=$payment_method, 
			cus_address='$cus_address',
			delivered_date='$delivered_date'
			shipping_fee=$shipping_fee,
			total_money=$total_money,
			WHERE id=$id";

		if ($conn->query($sql) === TRUE) {
			return true;
		}
		echo "Error: " . $sql . PHP_EOL . $conn->error;
		return false;
	}

	function updateStatusCancel($order_id, $status)
	{
		global $conn;
		$sql = "UPDATE `order` SET status=$status WHERE id=$order_id";
		if ($conn->query($sql) === TRUE) {
			// 2. Lấy danh sách mặt hàng trong đơn hàng
			$select = "SELECT * FROM `order_item` WHERE order_id=$order_id";
			$result = $conn->query($select);
	
			if ($result && $result->num_rows > 0) {
				while ($row = $result->fetch_assoc()) {
					$sql1 = "UPDATE entry_details 
					SET p_inventory = p_inventory + $row[qty] 
					WHERE product_id = $row[product_id] AND entercoupon_id = $row[batch_id]";
					$conn->query($sql1);
				}
			}
			return true;
		}
		// Ghi log lỗi thay vì echo
		error_log("Error updating order status: " . $conn->error);
		return false;
	}

	function delete($order)
	{
		global $conn;
		$orderItemRepository = new OrderItemRepository();
		$orderItems = $order->getOrderItems();

		// Xóa các mục đơn hàng
		foreach ($orderItems as $orderItem) {
			if (!$orderItemRepository->delete($orderItem)) {
				echo "Error deleting order item";
				return false;
			}
		}

		// Sử dụng Prepared Statements để tránh SQL injection
		$id = $order->getId();
		$sql = "DELETE FROM `order` WHERE id=?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("i", $id);

		// Thực thi câu lệnh
		if ($stmt->execute()) {
			return true;
		} else {
			echo "Error deleting order: " . $conn->error;
			return false;
		}
	}


	function getBy($array_conds = array(), $array_sorts = array(), $page = null, $qty_per_page = null)
	{
		if ($page) {
			$page_index = $page - 1;
		}

		$temp = array();
		foreach ($array_conds as $column => $cond) {
			$type = $cond['type'];
			$val = $cond['val'];
			$str = "$column $type ";
			if (in_array($type, array("BETWEEN", "LIKE"))) {
				$str .= "$val";
			} else {
				$str .= "'$val'";
			}
			$temp[] = $str;
		}
		$condition = null;

		if (count($array_conds)) {
			$condition = implode(" AND ", $temp);
		}

		$temp = array();
		foreach ($array_sorts as $key => $sort) {
			$temp[] = "$key $sort";
		}
		$sort = null;

		if (count($array_sorts)) {
			$sort = "ORDER BY " . implode(" , ", $temp);
		}

		$limit = null;
		if ($qty_per_page) {
			$start = $page_index * $qty_per_page;
			$limit = "LIMIT $start, $qty_per_page";
		}


		return $this->fetchAll($condition, $sort, $limit);
	}
}
