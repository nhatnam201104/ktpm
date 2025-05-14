<?php
$name = 'Thêm/Sửa Sản Phẩm';
$baseUrl = '../';
require_once('../layouts/header.php');
if ($_SESSION["user"]["role_id"] != 3 && $_SESSION["user"]["role_id"] != 2) {
	echo 'Cannot access';
	die();
}
$id = $featured_image = $name = $brand_id =  $category_id = $description = '';

$id = getGet('id');
if ($id != '' && $id > 0) {
	$sql = "select * from Product where id = '$id'";
	$userItem = executeResult($sql, true);
	if ($userItem != null) {
		$featured_image = $userItem[0]['featured_image'];
		$name = $userItem[0]['name'];
		$brand_id = $userItem[0]['brand_id'];
		$discount_id = $userItem[0]['discount_id'];
		$category_id = $userItem[0]['category_id'];
		$description = $userItem[0]['description'];
	} else {
		$id = 0;
	}
} else {
	$id = 0;
}

$sql = "select * from category where deleted=0";
$categoryItems = executeResult($sql);
$sql = "select * from brand";
$brandItems = executeResult($sql);
$sql = "select * from discount where display=0";
$discountItems = executeResult($sql);

?>
<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<div class="row" style="margin-top: 20px;">
	<div class="col-md-12 table-responsive">
		<h3>Sửa Sản Phẩm</h3>
		<div class="panel panel-primary">
			<div class="panel-body">
			<input required="true" type="hidden" class="form-control" id="id" name="id" value="<?= $id ?>">
				<div class="row">
					<div class="col-md-9 col-12">
						<div class="form-group">
							<label for="usr">Tên Sản Phẩm:</label>
							<input required="true" type="text" class="form-control" id="name" name="name" value="<?= $name ?>">
							<input type="text" name="id" value="<?= $id ?>" hidden="true">
						</div>
						<div class="form-group">
							<label for="pwd">Nội Dung:</label>
							<textarea class="form-control" rows="5" name="description" id="description"><?= $description ?></textarea>
						</div>

						<button class="btn btn-success" id="saveButton">Lưu Sản Phẩm</button>
					</div>
					<div class="col-md-3 col-12" style="border: solid grey 1px; padding-top: 10px; padding-bottom: 10px;">
						<div class="form-group">
							<label for="usr">Danh Mục Sản Phẩm:</label>
							<select class="form-control" name="category_id" id="category_id" required="true">
								<option value="">-- Chọn --</option>
								<?php
								foreach ($categoryItems as $item) {
									if ($item['id'] == $category_id) {
										echo '<option selected value="' . $item['id'] . '">' . $item['name'] . '</option>';
									} else {
										echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
									}
								}
								?>
							</select>
						</div>
						<div class="form-group">
							<label for="usr">Giảm giá:</label>
							<select class="form-control" name="discount_id" id="discount_id" required="true">
								<option value="">-- Chọn --</option>
								<?php
								foreach ($discountItems as $item) {
									if ($item['id'] == $discount_id) {
										if ($discount_id == 0) {
											echo '<option selected value="' . $item['id'] . '">Không Chọn</option>';
										} else {
											echo '<option selected value="' . $item['id'] . '">' . $item['name'] . '</option>';
										}
									} else {
										echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
									}
								}
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="alert-overlay" id="alert-overlay">
	<div class="alert-dialog">
		<h2>Thông Báo</h2>
		<p id="alert-message"></p>
		<p>Giá trị alert: <span id="alert-value"></span></p>
		<button onclick="hideAlert()">Đóng</button>
	</div>
</div>

<!-- <script type="text/javascript">
	function updateThumbnail() {
		$('#thumbnail_img').attr('src', $('#featured_image').val())
	}
</script> -->
<script>
	document.getElementById('saveButton').addEventListener('click', function() {
		const id = document.getElementById('id').value;
		const name = document.getElementById('name').value;
		const category_id = document.getElementById('category_id').value;
		const discount_id = document.getElementById('discount_id').value;
		const description = document.getElementById('description').value;
		// const old_img = document.getElementById('old_featured_image').value;
		// const featured_image = document.getElementById('featured_image').files[0];
		const method = 'PUT';
		const url = './form_save.php';

		const data = JSON.stringify({
			id: parseInt(id) || 0,
			name,
			category_id,
			discount_id,
			description
			// old_img,
			// featured_image
		});
		fetch(url, {
				method: method,
				headers: {
					'Content-Type': 'application/json',
				},
				body: data,
			})
			.then(response => response.json())
			.then(result => {
				if (result.code === 200) {
					alert(result.message);
					location.reload();
				} else {
					alert('Đã xảy ra lỗi: ' + (result.message || 'Không rõ nguyên nhân'));
				}
			})
			.catch(error => {
				console.error('Error:', error);
				alert('Lỗi khi gửi yêu cầu đến server!');
			});
	});
</script>

<?php
require_once('../layouts/footer.php');
?>