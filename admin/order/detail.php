<?php
	$title = 'Thông Tin Chi Tiết Đơn Hàng';
	$baseUrl = '../';
	require_once('../layouts/header.php');
	if ($_SESSION["user"]["role_id"] != 4 && $_SESSION["user"]["role_id"] != 2) {
		echo 'Cannot access';
		die();
	}
	require_once('../../utils/utility.php');
	$orderId = getGet('id');

	$sql = "SELECT order_item.*, Product.name, Product.featured_image FROM order_item LEFT JOIN Product ON Product.id = order_item.product_id WHERE order_item.order_id = $orderId";
	$data = executeResult($sql);
	

	$sql = "SELECT * FROM `order` WHERE id = $orderId";
	$orderItem = executeResult($sql);

?>

<div class="row" style="margin-top: 20px;">
	
<div class="col-md-5 border border-primary rounded">
    <form id="statusForm">
        <label for="status">Trạng Thái Đơn Hàng:</label>
		<?php
			if ($orderItem[0]['status'] == 0) {
				echo '
					<select id="status" class="form-control" onchange="changeOrderStatus(' . $orderId . ', this.value)">
						<option value="0" ' . ($orderItem[0]['status'] == 0 ? 'selected' : '') . '>Waiting</option>
						<option value="1" ' . ($orderItem[0]['status'] == 1 ? 'selected' : '') . '>Đã liên lạc</option>
						<option value="2" ' . ($orderItem[0]['status'] == 2 ? 'selected' : '') . '>Đã giao</option>
						<option value="3" ' . ($orderItem[0]['status'] == 3 ? 'selected' : '') . '>Hủy</option>
					</select>
				';
			} elseif ($orderItem[0]['status'] == 1)
			{
				echo '
					<select id="status" class="form-control" onchange="changeOrderStatus(' . $orderId . ', this.value)">
						<option value="1" ' . ($orderItem[0]['status'] == 1 ? 'selected' : '') . '>Đã liên lạc</option>
						<option value="2" ' . ($orderItem[0]['status'] == 2 ? 'selected' : '') . '>Đã giao</option>
						<option value="3" ' . ($orderItem[0]['status'] == 3 ? 'selected' : '') . '>Hủy</option>
					</select>
				';
			}elseif ($orderItem[0]['status'] == 2)
			{
				echo '
					<select id="status" class="form-control" onchange="changeOrderStatus(' . $orderId . ', this.value)">
						<option value="2" ' . ($orderItem[0]['status'] == 2 ? 'selected' : '') . '>Đã giao</option>
					</select>
				';
			}elseif ($orderItem[0]['status'] == 3)
			{
				echo '
					<select id="status" class="form-control" onchange="changeOrderStatus(' . $orderId . ', this.value)">
						<option value="3" ' . ($orderItem[0]['status'] == 3 ? 'selected' : '') . '>Hủy</option>
					</select>
				';
			}

		?>
    </form>
</div>
	<div class="col-md-6  border border-primary rounded  text-secondary "  style="margin-left: 22px;">
		<table class="table table-bordered table-hover" style="margin-top: 20px;" style="margin-top: 20px;">
			<tr>
				<th>Họ & Tên: </th>
				<td><?php echo isset($orderItem[0]['cus_fullname']) ? $orderItem[0]['cus_fullname'] : ''; ?></td>
			</tr>
			
			<tr>
				<th>Địa Chỉ: </th>
				<td><?php echo isset($orderItem[0]['cus_address']) ? $orderItem[0]['cus_address'] : ''; ?></td>
			</tr>
			<tr>
				<th>Phone: </th>
				<td><?php echo isset($orderItem[0]['cus_mobile']) ? $orderItem[0]['cus_mobile'] : ''; ?></td>
			</tr>
		</table>
	</div>
	<div class="col-md-12">
		<h3>Chi Tiết Đơn Hàng</h3>
	</div>
	<div class="col-md-8 table-responsive">
		<table class="table table-bordered table-hover" style="margin-top: 20px;">
			<thead class="thead-light">
				<tr>
					<th>STT</th>
				
					<th>Tên Sản Phẩm</th>
					<th>Giá</th>
					<th>Số Lượng</th>
					<th>Tổng Giá</th>
				</tr>
			</thead>
			<tbody>
<?php
	$index = 0;
	foreach($data as $item) {
		echo '<tr>
					<th>'.(++$index).'</th>
				
					<td>'.$item['name'].'</td>
					<td>'.$item['unit_price'].'</td>
					<td>'.$item['qty'].'</td>
					<td>'.$item['total_price'].'</td>
				</tr>';
			
			}
?>
				<tr>
					<td></td>
					
					<td></td>
					<td></td>
					<th>Tổng Tiền</th>
					<th><?php echo isset($orderItem[0]['total_money']) ? $orderItem[0]['total_money'] : ''; ?></th>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
    function changeOrderStatus(orderId, status) {
        // Gửi yêu cầu cập nhật trạng thái của đơn hàng
        $.post('form_api.php', {
            'id': orderId,
            'status': status,
            'action': 'update_status'
        }, function(data) {
            if (data != null && data != '') {
                // Xử lý phản hồi từ máy chủ nếu cần
            }
            // Tải lại trang để cập nhật dữ liệu
            location.reload();
        });
    }
</script>
<?php
	require_once('../layouts/footer.php');
?>