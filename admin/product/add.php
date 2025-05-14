<?php
$name = 'Thêm/Sửa Sản Phẩm';
$baseUrl = '../';
require_once('../layouts/header.php');

if ($_SESSION["user"]["role_id"] != 3 && $_SESSION["user"]["role_id"] != 2) {
	echo 'Cannot access';
	die();
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
		<h3>Thêm Sản Phẩm</h3>
		<div class="panel panel-primary">
			<div class="panel-body">
				<form action="" enctype="multipart/form-data" method="POST" id="productForm">
					<div class="row">
						<div class="col-md-9 col-12">
							<div class="form-group">
								<label for="usr">Tên Sản Phẩm:</label>
								<input type="text" class="form-control" id="name" name="name">
							</div>
							<div class="form-group">
								<label for="pwd">Nội Dung:</label>
								<textarea rows="5" class="form-control" name="description" id="description" required></textarea>
							</div>
							<button class="btn btn-success" id="saveButton">Lưu Sản Phẩm</button>
						</div>
						<div class="col-md-3 col-12" style="border: solid grey 1px; padding-top: 10px; padding-bottom: 10px;">
							<div class="form-group">
								<label for="featured_image">featured_image:</label>
								<input required="true" type="file" class="form-control" id="featured_image" name="featured_image" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
							</div>
							<div class="form-group">
								<label for="usr">Danh Mục Sản Phẩm:</label>
								<select class="form-control" name="category_id" id="category_id" required="true">
									<option value="">-- Chọn --</option>
									<?php
									foreach ($categoryItems as $item) {
											echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';										
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
											echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';										
									}
									?>
								</select>
							</div>
						</div>
					</div>
                </form>
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

<script type="text/javascript">
	function updateThumbnail() {
		$('#thumbnail_img').attr('src', $('#featured_image').val())
	}
</script>
<script>
 	document.getElementById('saveButton').addEventListener('click', function() {
     const name = document.getElementById('name').value;
     const category_id = document.getElementById('category_id').value;
     const discount_id = document.getElementById('discount_id').value;
     const description = document.getElementById('description').value;
     const featured_image = document.getElementById('featured_image').files[0];
     const formData = new FormData(); // Tạo formData để gửi dưới dạng multipart/form-data
     formData.append('name', name);
     formData.append('category_id', category_id);
     formData.append('discount_id', discount_id);
     formData.append('description', description);
 	 formData.append('featured_image', featured_image); // Gửi file
	 console.log(description);
     // if (featured_image) {
     // }
     const method ='POST';
     const url = './form_save.php';
     fetch(url, {
         method: method,
         body: formData, // Gửi formData
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

	// $('#description').summernote({
	// 	placeholder: 'Nhập nội dung dữ liệu',
	// 	tabsize: 2,
	// 	height: 300,
	// 	toolbar: [
	// 		['style', ['style']],
	// 		['font', ['bold', 'underline', 'clear']],
	// 		['color', ['color']],
	// 		['para', ['ul', 'ol', 'paragraph']],
	// 		['table', ['table']],
	// 		['insert', ['link', 'picture', 'video']],
	// 		['view', ['fullscreen', 'codeview', 'help']]
	// 	]
	// });
</script>

<?php
require_once('../layouts/footer.php');
?>