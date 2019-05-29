<?php
session_start();
ob_start();
?>
<html>
<head>
<title>Đổi mật khẩu</title>
<meta charset="utf-8">
<title>Đổi mât khẩu</title>
    <link rel="stylesheet" href="../css/3.4.0.bootstrap.min.css">
    <script src="../js/3.3.1.jquery.min.js"></script>
    <script src="../js/3.4.0.bootstrap.min.js"></script>
	 	<link rel="icon" type="image/png" href="../images/qnict.ico"/>
   <link rel="stylesheet" href="../css/StyleAdmin.css">
    <style>
     
        body {
    width: 1200px;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
        select,input {
    height: 32px;
    margin: 5px 5px; }

    </style>
</head>
<body>
<?php
//kiểm tra xem đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
}

?>
<?php
 
 //Gọi file connection.php
 require_once("../include/connection.php");
 $notice=-1;
 if (isset($_POST["btn_submit"])) {
     // Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
     $old_pass = @($_POST['old_pass']);
     $new_pass = @$_POST['new_pass'];
     $re_new_pass = @$_POST['re_new_pass'];
     //Làm sạch
     $new_pass = strip_tags($new_pass);
     $new_pass = addslashes($new_pass);
     $old_pass = strip_tags($old_pass);
     $old_pass = addslashes($old_pass);
     $re_new_pass = strip_tags($re_new_pass);
     $re_new_pass = addslashes($re_new_pass);
     //Lấy biến username lưu trên session
     $username=$_SESSION['username'];
     //lấy mật khẩu cũ
     $sql = "select * from tbladmin where username = '$username' ";
     $query = mysqli_query($conn, $sql);
     while ($data = mysqli_fetch_array($query)) {
         $hash=$data["hash"];
     }
 
     if  ($old_pass ==""||$new_pass==""||$re_new_pass=="") {
        $notice= 1;// echo 'Không được để trống.';
     }
        // Ngược lại nếu mật khẩu cũ nhập đúng
    elseif (!password_verify($old_pass, $hash)) {
         $notice= 4;// echo 'Mật khẩu cũ nhập không chính xác.';
    }
    //nếu mật khẩu mởi nhập lại không khớp
     elseif ($new_pass != $re_new_pass) {
        $notice= 2;// echo 'Mật khẩu mới và mật khẩu nhập lại không khớp nhau.';
     }
     // Ngược lại nếu độ dài mật khẩu mới nhỏ hơn 6 ký tự
     elseif (strlen($new_pass) < 6) {
        $notice= 3;// echo 'Mật khẩu phải lớn hơn hoặc bằng 6 ký tự.';
     }
  
     // Ngược lại
        else {
   //Mã hóa mật khẩu bằng thuật toán bcrypt
         $options = [ 'cost' => 12];
         $password=password_hash($new_pass, PASSWORD_BCRYPT, $options);
 
         // Lệnh SQL đổi mật khẩu
         $sql = "UPDATE `tbladmin` SET `hash`='$password' WHERE `username`='$username'";
         // Thực hiện truy vấn
         mysqli_query($conn, $sql);
      
         // Hiển thị thông báo và tải lại trang
         $notice= 0;// echo 'Đổi mật khẩu thành công.';
         $old_pass=$new_pass=$re_new_pass="";
     }
 }
  
 ?>
 <body>
    <div class="top">
        <img src='../images/adminbanner1.png' style="display: block; margin-left: auto; margin-right: auto;">
    </div>
    <div id="menungang" style=" margin-bottom: 5px; ">
        <div class="container1">
            <ul class="menu">
                <li ><a  href="">Quản trị</a>
                    <ul class="submenu">
                        <li><a href="accounts.php">Quản trị tài khoản</a>
                        <li><a href="department.php">Quản trị phòng ban</a>
                        <li><a href="member.php">Quản trị nhân viên</a>

                    </ul>
                </li>

                <li><a href="view.php">Lịch công tác</a></li>
                <li><a href="schedule.php">Quản trị lịch</a></li>
                <li><a href="notice.php">Thông báo</a></li>
                <li><a href="event.php">Ngày lễ</a></li>
                <li><a href="change-password.php"> Đổi mật khẩu</a></li>
                <li><a href="logout.php">Đăng xuất</a></li>

            </ul>
            <div class="animation start-home"></div>
        </div>

    </div>
    <div style="background-color: #f7fcff; min-height: 700px;">
    <fieldset>
    <legend>Đổi mật khẩu</legend>
<form method="POST" action="change-password.php"  style="padding-left: 25px;">

   
     <table>
     <tr>
                        <td></td>

                        
                        <td id="notice">
                                 <?php 
                            if($notice==0) echo '<div class="alert-box success"><span>Thành công: </span>Yêu cầu đã được thực hiện.</div>';
                            elseif($notice==1) echo'<div class="alert-box warning"><span>Cảnh báo: </span>Không được để trống.</div>';
                            elseif($notice==2) echo'<div class="alert-box warning"><span>Cảnh báo: </span>Mật khẩu mới và mật khẩu nhập lại không khớp nhau.</div>';
                            elseif($notice==3) echo'<div class="alert-box warning"><span>Cảnh báo: </span>Mật khẩu phải lớn hơn hoặc bằng 6 ký tự.</div>';
                             elseif($notice==4) echo'<div class="alert-box warning"><span>Cảnh báo: </span>Mật khẩu cũ nhập không chính xác.</div>';
                           
                        ?>

                        </td>
                    </tr>
     <tr>
     <td>Mật khẩu cũ <span style="color:red;">*</span></td>
     <td><input type="password" name="old_pass"  value="<?php if(isset($old_pass) && $old_pass != NULL){ echo $old_pass; } ?>"></td>
     </tr>
     <tr>
     <td>Mật khẩu mới <span style="color:red;">*</span></td>
     <td><input type="password" name="new_pass"  value="<?php if(isset($new_pass) && $new_pass != NULL){ echo $new_pass; } ?>"></td>
     </tr>
     <tr>
     <td>Nhập lại mật khẩu <span style="color:red;">*</span></td>
     <td><input type="password" name="re_new_pass"  value="<?php if(isset($re_new_pass) && $re_new_pass != NULL){ echo $re_new_pass; } ?>"></td>
     </tr>
     <tr>
         <td></td>
     <td colspan="2"> <input id="nut" name="btn_submit" type="submit" value="Thực hiện"></td>
     </tr>
     </table>
 
  </form>
  </fieldset>
  </div>

<footer class="container-fluid text-center">
    <p>Bản quyền thuộc về Trung tâm Công nghệ thông tin và Truyền thông Quảng Ninh
    </p>
    <p>All rights reserved.</p>
</footer>

</body>

</html>
<?php ob_end_flush(); ?>