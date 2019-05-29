<?php
session_start();
ob_start();
?>
<html>

<head>

      <link rel="stylesheet" href="../css/styleLichCT.css">
 	<link rel="icon" type="image/png" href="../images/qnict.ico"/>
    <meta charset="utf-8">
    <title>Đăng nhập</title>
    
<style>

.alert-box {
	color: #555;
	border :1px solid;
	border-radius: 10px;
  padding: 10px 5px;
  width: 300px;
	font-size: 12px;
	
	margin-top: 20px;
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

<body style="background-image: url(../images/backgroundDN.PNG); font-famiy:  Roboto, sans-serif; background-repeat: no-repeat;">
  <?php
//kiểm tra xem đã đăng nhập chưa
if (isset($_SESSION['username']) == true) {
	// Nếu người dùng đã đăng nhập thì chuyển hướng website sang trang schedule.php
  header('Location: ../manager/schedule.php');
}

?>
  <?php
//Gọi file connection.php
require_once("../include/connection.php");
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

	
$sql = "select * from tbladmin where username = '$username' ";
$query = mysqli_query($conn,$sql);
$hash=null;
while ( $data = mysqli_fetch_array($query) ){
	$hash=$data["hash"];
	$auth= $data["auth"];
}
if ($hash==null) $notice=1; elseif (password_verify($password,$hash)) {
	//tiến hành lưu info vào session 
$_SESSION['username'] = $username;
$_SESSION['auth'] =$auth;
// Thực thi hành động sau khi lưu thông tin vào session
// chuyển hướng trang web tới trang là ....php
header('Location: view.php');
}else{
   $notice=1;
}
}
}
?>
  

  

    <br><br>
   

    <form style:" font-family: Roboto, sans-serif; "  method="POST" name="frmdn" action="login.php">
      <br>
      <h1 style="color: #c32119" align="center">Đăng nhập quản trị</h1>
      <table>
        <tr>

          <td><input style=" font-size: 17px;" type="text" placeholder="Tên đăng nhập" name="username" required=""></td>
        </tr>
        <tr>

          <td><input style=" font-size: 17px;" type="password" placeholder="Mật khẩu" name="password" required=""></td>
        </tr>
        <tr>
          <td colspan="1" align="center"> <input name="btn_submit" type="submit" value="Đăng nhập"></td>
        </tr>

        <tr>
          <td colspan="1" align="center">  
          <?php 
                if($notice==1) echo'<div class="alert-box error"><span>Lỗi: </span>Tài khoản hoặc mật khẩu không đúng.</div>';
          ?>
          </td>
        </tr>
        <tr><td colspan="1" align="center"></td></tr>
      </table>
     
    </form>
   
  </body>

  </html>
  <?php ob_end_flush(); ?>