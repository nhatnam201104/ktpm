<?php

session_start();
require_once('../../utils/utility.php');

require_once('../../utils/App.php');
require_once('../../database/dbhelper.php');
require_once('../../database/Database.php');


$user = getUserToken();
if ($user != null) {
	header('Location: ../');
	die();
}
?>



<!DOCTYPE html>
<html>

<head>
	<title>Đăng nhập</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../../assets/css/dashboard.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

	<style>
		body {
			background-image: url(https://giphy.com/gifs/sky-clouds-beauty-Pp9W9tuVF5NwQ);
			background-size: cover;
			/* để hình ảnh tự động điều chỉnh kích thước để phù hợp với kích thước của phần tử */
			/* Các thuộc tính khác tùy chỉnh */
			background-repeat: no-repeat;
		}

		.error-message {
			color: red;
			font-size: 0.8rem;
			font-style: italic;
			margin-left: 1rem;
			margin-bottom: 0.5rem;
		}

		.panel {
			box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
		}

		.label-wrap {
			margin: 0;
		}
	</style>
</head>

<body>


	<section class="h-80 gradient-form gra-bgcolor">
		<div class="container py-5 h-80">
			<div class="row d-flex justify-content-center align-items-center h-80">
				<div class="col-xl-13">
					<div class="card rounded-3 text-black">
						<div class="row g-0">
							<div class="col-lg-6">
								<div class="card-body p-md-5 mx-md-4">

									<div class="text-center">
										<img src="" style="width: 185px;" alt="">
										<h4 class="mt-1 mb-5 pb-1">Đăng nhập tài khoản</h4>
									</div>
									<h5 style="color : red;" class="text-center"></h5>


									<div class="panel-body">
										<form method="POST">
											<div class="form-group">
												<div class="d-flex align-items-center">
													<label for="email">Email:</label>
													<div id="error-email" class="error-message"></div>
												</div>
												<!-- value="<?= $email ?>" -->
												<input type="email" onchange="checkEmail()" class="form-control" id="email" name="email">

											</div>

											<div class="form-group">
												<div class="d-flex align-items-center">
													<label for="password">Mật Khẩu:</label>
													<div id="error-password" class="error-message"></div>
												</div>
												<input type="password" onchange="checkPassword()" class="form-control" id="password" name="password" minlength="6">
											</div>
											<!-- <p class="d-flex align-items-center justify-content-center">
												<a href="register.php">
													Đăng kí tài khoản mới
												</a>
											</p> -->
										</form>
										<div class="d-flex align-items-center justify-content-center">
											<button class="btn btn-success w-50" id="dn">Đăng nhập</button>
											<!-- btn btn-success w-50 -->
										</div>
									</div>
									<div class="text-center pt-1 mb-5 pb-1">

										<!-- <a class="text-muted" href="#!">Forgot password?</a> -->
									</div>




								</div>
							</div>
							<div class="col-lg-6 d-flex align-items-center " style="background-color: pink;">
								<div class=" px-3 py-4 p-md-5 mx-md-4">
									<h3 class="mb-4">MOBILE STORE</h3>
									<p class="medium mb-0">Chào mừng bạn đến với trang mua sắm của chúng tôi! Đăng nhập để trải nghiệm các tính năng độc quyền, theo dõi đơn hàng và quản lý thông tin cá nhân của bạn
										. Nếu bạn chưa có tài khoản, hãy đăng kí ngay để trở thành thành viên của cộng đồng của chúng tôi và nhận được nhiều ưu đãi hấp dẫn</p>
									<div class="d-flex align-items-center justify-content-center pb-4">



									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</body>
<script type="text/javascript">
	let inputEmail = $('#email');
	let inputPassword = $('#password');

	function isValidEmail(email) {
		return /^[^\.\s][\w\-]+(\.[\w\-]+)*@([\w-]+\.)+[\w-]{2,}$/gm.test(email);
	}

	function validateEmail() {
		if (isValidEmail(inputEmail.val())) {
			$('#error-email').empty();
		} else {
			$('#error-email').html('* Vui lòng điền đúng định dạng email!');
		}
	}

	function isValidPassword(password) {
		return password.length > 6;
	}

	function checkEmail() {
		if (isValidEmail(inputEmail.val())) {
			$('#error-email').empty();
			let email = $('#email').val();
			$.ajax({
				url: "process-login.php/checkEmail",
				data: {
					email: email
				},
				method: "POST",
				success: function(response) {
					if (response === "1") {
						$('#error-email').html('*Email không tồn tại');
					} else {
						// Xóa thông báo lỗi nếu email hợp lệ
						$('#error-email').html('');
					}
				}
			});
		} else {
			$('#error-email').html('* Vui lòng điền đúng định dạng email!');
		}
	}

	function checkPassword() {
		if (isValidPassword(inputPassword.val())) {
			$('#error-password').empty();
			let email = $('#email').val();
			let password = $('#password').val();
			$.ajax({
				url: "process-login.php/checkPassword",
				data: {
					email: email,
					password: password
				},
				method: "POST",
				success: function(response) {
					if (response === "1") {
						$('#error-password').html('*Mật khẩu sai!');
					} else {
						// Xóa thông báo lỗi nếu email hợp lệ
						$('#error-email').html('');
					}
				}
			});
		} else {
			$('#error-password').html('*Mật khẩu phải có hơn 6 kí tự!');
		}
	}
	$('#email').on('input', checkEmail);
	$('#password').on('input', checkPassword);

	$(document).ready(function() {
		$('#dn').click(function() {
			let email = inputEmail.val();
			let password = inputPassword.val();
			if (email === '') {
				$('#error-email').html(`*Email không được để trống`);
				event.preventDefault();
			} else if (password === '') {
				$('#error-password').html(`*Password không được để trống`);
				event.preventDefault();
			} else {
				$.ajax({
					url: "process-login.php/logIn",
					method: "POST",
					data: {
						email: email,
						password: password
					},
					success: function(response) {
						console.log(response);
						// Use user object to check role_id and redirect accordingly
						if (response == 1) {
							alert('Đăng nhập thành công!');
							window.location.href = 'http://localhost/Web2_liquen/admin/index.php';
						} else if (response === "5") {
							alert('Đăng nhập thành công!');
							window.location.href = 'http://localhost/Web2_liquen/site/index.php';
						} else if (response === "2") {
							alert('Đăng nhập thành công!');
							window.location.href = 'http://localhost/Web2_liquen/admin/index.php';
						} else if (response === "3") {
							alert('Đăng nhập thành công!');
							window.location.href = 'http://localhost/Web2_liquen/admin/index.php';
						} 
						else if (response === "4") {
							alert('Đăng nhập thành công!');
							window.location.href = 'http://localhost/Web2_liquen/admin/index.php';
						}
						else if (response == 0) {
							alert('Đăng nhập thất bại');
						}
					}
				})
			}
		});
	});
</script>

</html>