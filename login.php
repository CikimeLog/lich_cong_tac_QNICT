<?php
session_start();
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Đăng nhập</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/qnict.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login/fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="login/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login/vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="login/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="login/css/util.css">
	<link rel="stylesheet" type="text/css" href="login/css/main.css">
<!--===============================================================================================-->


<style>

.alert-box {
	color: #555;
	border :1px solid;
	border-radius: 10px;
  padding: 10px 5px;
  width: 300px;
	font-size: 12px;
	
	margin-top: 20px;
	display: block; margin-left: auto; margin-right: auto;
}
.alert-box span {
	font-weight: bold;
	
}
.error {
	background: #ffecec url('images/error.png') no-repeat 10px 50%;
	border-color: #f5aca6;
}
.error span {
	color: #E24F26;
}
</style>
    
</head>

</head>
<body>
<?php
//kiểm tra xem đã đăng nhập chưa
if (isset($_SESSION['member_id']) == true) {
	// Nếu người dùng đã đăng nhập thì chuyển hướng website sang trang index.php
  header('Location: index.php');
}
//
?>
  <?php
//Gọi file connection.php
require_once("include/connection.php");
$notice=-1;
// nếu người dùng ân nút đăng nhập thì xử lý
if (isset($_POST["btn_submit"])) {
// lấy thông tin người dùng
$username = $_POST["username"];
$password = $_POST["password"];
//làm sạch thông tin, xóa bỏ các tag html, ký tự đặc biệt
//mà người dùng cố tình thêm vào để tấn công theo phương thức sql injection
$username = strip_tags($username);
$username = addslashes($username);
$password = strip_tags($password);
$password = addslashes($password);
if ($username == "" || $password =="") {
echo "username hoặc password bạn không được để trống!";
}else{

	
$sql = "select * from tblmember where Member_User = '$username' ";
$query = mysqli_query($conn,$sql);
$hash=null;
$id=null;
while ( $data = mysqli_fetch_array($query) ){
	$hash=$data["Member_Hash"];
    $name=$data["Member_Name"];
    $id=$data["Member_ID"];
    
}
if ($id==null) $notice=1; elseif (password_verify($password,$hash)) {
	//tiến hành lưu info vào session 
$_SESSION['Member_Name'] = $name;
$_SESSION['Member_ID'] =$id;
// Thực thi hành động sau khi lưu thông tin vào session
// chuyển hướng trang web tới trang là ....php
header('Location: index.php');
}else{
   $notice=1;
}
}
}
?>
 
	<div class="limiter">
		<div class="container-login100" style="background-image: url('login/images/bg-01.jpg');">
			<div class="wrap-login100">
				<form class="login100-form validate-form"  method="POST" name="frmdn" action="login.php">
					
                    <img style="display: block; margin-left: auto; margin-right: auto;"  src="login/images/qnict.png">
					

					<span class="login100-form-title p-b-34 p-t-27">
						đăng nhập để xem lịch
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Enter username">
						<input class="input100" type="text" name="username" placeholder="Tên đăng nhập">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="password" placeholder="Mật khẩu">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

				

					<div class="container-login100-form-btn">
						<button  name="btn_submit"  type="submit" class="login100-form-btn">
							Đăng nhập
						</button>
                    </div>
                    

                    <div class="">
						 <?php 
                if($notice==1) echo'<div  class="alert-box error"><span> &nbsp;  &nbsp;  &nbsp;Lỗi: </span>Tài khoản hoặc mật khẩu không đúng.</div>';
          ?>
					</div>



					<div class="text-center p-t-90">
						<a class="txt1" href="index.php">
                        ⇦
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="login/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="login/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="login/vendor/bootstrap/js/popper.js"></script>
	<script src="login/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="login/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="login/vendor/daterangepicker/moment.min.js"></script>
	<script src="login/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="login/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="login/js/main.js"></script>

</body>
</html>
  <?php ob_end_flush(); ?>