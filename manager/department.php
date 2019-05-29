<?php
session_start();
ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Quản trị phòng ban</title>
    <link rel="stylesheet" href="../css/3.4.0.bootstrap.min.css">
    <script src="../js/3.3.1.jquery.min.js"></script>
    <script src="../js/3.4.0.bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/StyleAdmin.css">

 	<link rel="icon" type="image/png" href="../images/qnict.ico"/>
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
<!--Hàm hiện thông báo xác nhận yêu cầu xóa-->
<SCRIPT LANGUAGE="JavaScript">
    function confirmAction() {
        return confirm("Bạn có chắc chắn muốn xóa?")
      }
 
</SCRIPT>

<?php
//kiểm tra xem đã đăng nhập chưa
if (!isset($_SESSION['username'])&&!isset($_SESSION['auth'])) {
  header('Location: login.php');
}
//kiểm tra xem tài khoản có quyền quản trị không
if ($_SESSION['auth'] != 1) {
  echo "Bạn không có quyền truy cập trang này. Hãy đăng nhập lại với tài khoản khác để tiếp tục.";
  //nếu không có thì k chạy
  die;
}
 //Gọi file connection.php
 require_once("../include/connection.php");
 $notice=-1;
?>
<!-----------code delete----------->
<?php
  if(isset($_GET['id'])&&isset($_GET['acction'])){
    $id =$_GET['id'];
    $id = strip_tags($id);
    $id = addslashes($id);
//kiểm tra id
  //câu lệnh sql lấy ra id của bảng tbldepartment
  $sql = "SELECT Department_ID  FROM `tbldepartment`" ;
  // thực thi câu $sql với biến conn lấy từ file connection.php
  $query = mysqli_query($conn,$sql);    
  $flag=0;
  while ($data = mysqli_fetch_array($query)) {
    if($data['Department_ID']==$id){$flag = 1;}
  }

    if($_GET['acction']=="delete" && $flag==1){
  // Lệnh SQL delete
      
  $sql = "DELETE FROM `tbldepartment` WHERE `Department_ID`='$id'";
  // Thực hiện truy vấn
 $query= mysqli_query($conn, $sql);
      
//  $sql = "DELETE FROM `tbladmin` WHERE `id_Department`='$id'";
//  // Thực hiện truy vấn
// $query= mysqli_query($conn, $sql);

if($query) {$notice= 0;

}// echo 'Đã xóa thành công 1 phòng, ban.';
  else $notice= 2;// echo 'Đã có lỗi xảy ra'; 

    }}


?>
<!-----------code update----------->
<?php

//cờ để xác định cập nhật hay thêm
$isUpdate=0;// thì là thêm
if(isset($_GET['id'])&&isset($_GET['acction'])){
  $id =$_GET['id'];
  $id = strip_tags($id);
  $id = addslashes($id);
  if($_GET['acction']=="update"){
  $isUpdate=1; //cập nhật
   //câu lệnh sql lấy ra  thông tin của bảng department
   $sql = "SELECT * FROM `tbldepartment` WHERE `Department_ID`='$id'" ;
            
   // thực thi câu $sql với biến conn lấy từ file connection.php
   $query= mysqli_query($conn, $sql);
   $data = mysqli_fetch_array($query);

   if($data==null){die('
    <button type="button" onclick="quay_lai_trang_truoc()">Quay lại trang trước</button>
  
    <script>
        function quay_lai_trang_truoc(){
            history.back();
        }
    </script>');}
   else{
    $Department_Name=$data["Department_Name"];
    $_SESSION['id']=$id;
   }
  }
  
}

if (isset($_POST["btn_submit_update"])) {
  // Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
  $Department_Name = @($_POST['Department_Name']);
  $id=$_SESSION['id'];
  //Làm sạch
  $Department_Name = strip_tags($Department_Name);
  $Department_Name = addslashes($Department_Name);
  if ($Department_Name =="") {
    $notice= 1;// echo 'Không được để trống.';
  }
  // Ngược lại
  else {
      // Lệnh SQL update
      echo $id;
      $sql = "UPDATE `tbldepartment` SET `Department_Name`='$Department_Name' WHERE `Department_ID`='$id'";
      // Thực hiện truy vấn
      
     $query= mysqli_query($conn, $sql);

   if($query) $notice= 0;// echo 'Đã cập nhật một phòng ban.';
      else  $notice= 2;// echo 'Đã có lỗi xảy ra';
  }
}

?>

<!-----------code thêm----------->
<?php  
if (isset($_POST["btn_submit_add"])) {
  // Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
  $Department_Name = @$_POST['Department_Name'];
  //Làm sạch
  $Department_Name = strip_tags($Department_Name);
  $Department_Name = addslashes($Department_Name);
  
 if ($Department_Name =="") {
  $notice= 1;// echo 'Không được để trống.';
  }

  // Ngược lại
  else {

      // Lệnh SQL đổi mật khẩu
      $sql = "INSERT INTO `tbldepartment`(`Department_Name`) VALUES ('$Department_Name')";
      // Thực hiện truy vấn
     $query= mysqli_query($conn, $sql);
 
   if($query)  $notice= 0;// echo 'Đã thêm 1 phòng ban.';
      else $notice= 2;// echo 'Đã có lỗi xảy ra';
  }
}

?>


<?php  
            //câu lệnh sql lấy ra all thông tin của bảng department
            $sql = "SELECT * FROM `tbldepartment`" ;
            
            // thực thi câu $sql với biến conn lấy từ file connection.php
            $query = mysqli_query($conn,$sql);           
          ?>


<body>
    <div class="top">
        <img src='../images/adminbanner1.png' style="display: block; margin-left: auto; margin-right: auto;">
    </div>
    <div id="menungang" style=" margin-bottom: 5px; ">
        <div class="container1">
            <ul class="menu">
                <li><a href="">Quản trị</a>
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

        </div>

    </div>
    <div style="background-color: #f7fcff; min-height: 700px;">

        <!----------- tạo form nhập----------->
        <fieldset>
            <legend>Quản trị phòng ban</legend>
            <form action="department.php" method="post" style="padding-left: 25px; padding-top: 10px;">
                <table>
                <tr>
                        <td></td>

                        
                        <td id="notice">
                                 <?php 
                            if($notice==0) echo '<div class="alert-box success"><span>Thành công: </span>Yêu cầu đã được thực hiện.</div>';
                            elseif($notice==1) echo'<div class="alert-box warning"><span>Cảnh báo: </span>Không được để trống</div>';
                            elseif($notice==2) echo'<div class="alert-box error"><span>Lỗi: </span>Đã có lỗi xảy ra</div>';
                           
                        ?>

                        </td>
                    </tr>
                    <tr>
                        <td> Tên phòng, ban <span style="color:red;">*</span> &nbsp; </td>
                        <td><input name="Department_Name" type="text" <?php if($isUpdate==1) echo ' value =\'' .$Department_Name.' \' ';?>>
                            
                                   </td>
    
                    </tr>
                    <tr>
                    <td></td>
                    <td colspan="2" align="center">
                        <span class="inline"><input id="nut" type="submit" <?php if ($isUpdate==0) echo ' name="btn_submit_add" value="Thêm" '; else echo ' name="btn_submit_update" value="Cập nhật" ';?>></span>
                        <span class="inline" style="float:right; <?php if($isUpdate==0) echo ' display:none; '?>" > <input id="nut" type="submit" name="btn_return" value="Quay lại"></span>
                        </td>
                    </tr>
               </table>
</form>
        </fieldset>
<!-- Tạo bảng và chèn dữ liệu cho bảng dstk-->
<table class="tabletable table-hover table-bordered results" style="margin-left: 10px; text-align: center;margin-right:10px; ">
  <caption>Danh sách phòng, ban</caption>
  <thead>
    <tr style="height: 45px; background-color: #fff0ea ">
      <th class="col-md-1 col-xs-1" style="text-align: center">STT</th>
      <th class="col-md-2 col-xs-2" style="text-align: center">Mã phòng ban</th>
      <th class="col-md-6 col-xs-6" style="text-align: center">Tên phòng ban</th>
      <th class="col-md-2 col-xs-2" style="text-align: center">Sửa</th>
      <th class="col-md-2 col-xs-2" style="text-align: center">Xóa</th>
    </tr>
     
  </thead>
  <tbody>
    <?php  
            $stt = 1 ;
            //câu lệnh sql lấy ra all thông tin của bảng admin và department
            $sql = "SELECT * FROM `tbldepartment`" ;
            
            // thực thi câu $sql với biến conn lấy từ file connection.php
            $query = mysqli_query($conn,$sql);
            //vòng lặp từ dòng data
          
            while ($data = mysqli_fetch_array($query)) {
          ?>
    <tr style="height: 40px;" <?php  
      if($isUpdate == 1 && $id == $data["Department_ID"] ) echo 'id="selected"';?> >
                        <th scope="row" style="text-align: center">
                            <?php echo $stt++ ?>
                        </th>
                        <td style="text-align: center">
                            <?php echo $data["Department_ID"]; ?>
                        </td>
                        <td>
                            <?php echo $data["Department_Name"]; ?>
                        </td>

                        <td><a href="department.php?<?php echo 'id='.$data["Department_ID"].'&acction=update'; ?>" >Sửa</a> </td>
                        <td><a href="department.php?<?php echo 'id='.$data["Department_ID"].'&acction=delete'; ?>" onclick="return confirmAction()">Xóa</a> </td>
                    </tr>
                    <?php
            }
          ?>
                    </tbody>
                </table>

    </div>

    <footer class="container-fluid text-center">
        <p>Bản quyền thuộc về Trung tâm Công nghệ thông tin và Truyền thông Quảng Ninh
        </p>
        <p>All rights reserved.</p>
    </footer>
</body>

</html>
<?php ob_end_flush(); ?>