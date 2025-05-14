<?php
$name = 'Hình ảnh Sản Phẩm';
$baseUrl = '../';
require_once('../layouts/header.php');
if ($_SESSION["user"]["role_id"] != 3 && $_SESSION["user"]["role_id"] != 2) {
	header("Location: ../index.php");
	die();
}
$id = $featured_image = '';

$id = getGet('id');
if ($id != '' && $id > 0) {
	$sql = "select * from Product where id = '$id'";
	$userItem = executeResult($sql, true);
	if ($userItem != null) {
		$featured_image = $userItem[0]['featured_image'];
	} else {
		$id = 0;
	}
} else {
	$id = 0;
}

?>
<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>

<div class="row" style="margin-top: 20px;">
	<div class="col-md-12 table-responsive">
		<h3>Hình ảnh sản phẩm</h3>
		<div class="panel panel-primary">
			<div class="panel-body">
			<input required="true" type="hidden" class="form-control" id="id" name="id" value="<?= $id ?>">
				<div class="row">
					<div class="col-md-12 col-12" style="border: solid grey 1px; padding-top: 10px; padding-bottom: 10px;">
						<div class="form-group">
							<label for="featured_image">featured_image:</label>
							<input type="file" class="form-control" id="featured_image" name="featured_image" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
							<img id="thumbnail_img" src="../<?= ($featured_image) ?>" style="max-height: 160px; margin-top: 5px; margin-bottom: 15px;">
							<input type="hidden" name="old_featured_image" id="old_featured_image" value="<?= $featured_image ?>">
						</div>
                        <button class="btn btn-success" id="saveButton">Lưu hình ảnh</button>
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
	$('#saveButton').click(function() {
    const id = $('#id').val();
    const old_img = $('#old_featured_image').val();
    const featured_image = $('#featured_image')[0].files[0];

    // Tạo FormData object để gửi cả file và data
    const formData = new FormData();
    formData.append('id', id);
    formData.append('old_img', old_img);
    formData.append('upload_img', '');
    if (featured_image) {
        formData.append('featured_image', featured_image);
    }

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
</script>

<?php
require_once('../layouts/footer.php');
?>