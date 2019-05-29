<?php
session_start();
ob_start();
?>
<!DOCTYPE html>
<html>


    <meta charset="utf-8">
    <title>Quản trị tài khoản</title>
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
 require_once("../include/connection.php");   $notice=-1;
?>

<!-----------code delete----------->
<?php
  if(isset($_GET['id'])&&isset($_GET['acction'])){
    $id =$_GET['id'];
    $id = strip_tags($id);
    $id = addslashes($id);
//kiểm tra 
  //câu lệnh sql lấy ra  của bảng tblmember
  $sql = "SELECT Member_ID FROM `tblmember`" ;
  // thực thi câu $sql với biến conn lấy từ file connection.php
  $query = mysqli_query($conn,$sql);    
  $flag=0;
  while ($data = mysqli_fetch_array($query)) {
    if($data['Member_ID']==$id){$flag = 1;}
  }

    if($_GET['acction']=="delete" && $flag==1){
  // Lệnh SQL delete
      
  $sql = "DELETE FROM `tblmember` WHERE `Member_ID`='$id'";
  // Thực hiện truy vấn
 $query= mysqli_query($conn, $sql);

if($query)  $notice= 0;// echo 'Đã xóa 1 cán bộ';
  else  $notice= 3;// echo 'Đã có lỗi xảy ra'; 

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
   //câu lệnh sql lấy ra all thông tin của bảng
   $sql = "SELECT * FROM `tblmember` WHERE Member_ID='$id'" ;
            
   // thực thi câu $sql với biến conn lấy từ file connection.php
   $query= mysqli_query($conn, $sql);
   $data = mysqli_fetch_array($query);

   if($data==null){  $notice= 2;//echo"Không tìm thấy cán bộ.";
  }
   else{
    $name=$data["Member_Name"];
    $zalo=$data["Member_ZaloID"];
    $email=$data["Member_Email"];
    $member_user= $data["Member_User"];
    $choose=$data["Member_Choose"];
    $_SESSION['tempID']=$id; 
   }
  }
}

if (isset($_POST["btn_submit_update"])) {
  // Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
  $name = @($_POST['name']);
  $zalo = @$_POST['zalo'];
  $email = @$_POST['email'];
  $choose = @$_POST['choose'];
 $member_user=@$_POST['member_user'];
 $member_pass=@$_POST['member_pass'];
  //Làm sạch
  $zalo = strip_tags($zalo);
  $zalo = addslashes($zalo);
  $email = strip_tags($email);
  $email = addslashes($email);
  $choose = addslashes($choose);
  $choose = strip_tags($choose);
  $name = strip_tags($name);
  $name = addslashes($name);
  $member_pass = strip_tags($member_pass);
  $member_pass = addslashes($member_pass);
  $member_user = strip_tags($member_user);
  $member_user = addslashes($member_user);
$id= $_SESSION['tempID'];
 //kiểm tra trùng 
  //câu lệnh sql lấy ra user của bảng admin
  $sql = "SELECT Member_ID FROM `tblmember`" ;
  // thực thi câu $sql với biến conn lấy từ file connection.php
  $query = mysqli_query($conn,$sql);    
  $flag=0;
  while ($data = mysqli_fetch_array($query)) {
    if($data['Member_ID']==$id){$flag = 1;}
  }
  if ($name =="") {
    $notice= 1;// echo 'Không được để trống.';
  }
  elseif ($flag == 0) {
    $notice= 2;//echo 'Lỗi không tồn tại cán bộ.';
}
  elseif ($email !=""&&filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $notice= 4;// echo 'Lỗi định dạng email.';
}
  // Ngược lại
  else {

      // Lệnh SQL update
      if($member_pass !=""){   //Mã hóa mật khẩu bằng thuật toán bcrypt
        $options = [ 'cost' => 12];
        $member_pass=password_hash($member_pass, PASSWORD_BCRYPT, $options); 
        $sql = "UPDATE `tblmember` SET `Member_Name`='$name',`Member_ZaloID`='$zalo',`Member_Email`='$email',`Member_Choose`='$choose',`Member_User`='$member_user' ,`Member_Hash`='$member_pass' WHERE `Member_ID`='$id'";
      }
          else
      $sql = "UPDATE `tblmember` SET `Member_Name`='$name',`Member_ZaloID`='$zalo',`Member_Email`='$email',`Member_Choose`='$choose',`Member_User`='$member_user' WHERE `Member_ID`='$id'";
      // Thực hiện truy vấn
     $query= mysqli_query($conn, $sql);

   if($query) $notice= 0;// echo 'Đã cập nhật cán bộ.';
      else  $notice= 3;// echo 'Đã có lỗi xảy ra';
  }
}

?>
<?php 
  function emailValid($string) 
    { 
        if (preg_match ("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+\.[A-Za-z]{2,6}$/", $string)) 
            return true; 
    } 
?>
<!-----------code thêm----------->
<?php  
if (isset($_POST["btn_submit_add"])) {
  // Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
  $name = @($_POST['name']);
  $zalo = @$_POST['zalo'];
  $email = @$_POST['email'];
  $member_user=@$_POST['member_user'];
  $member_pass=@$_POST['member_pass'];
  $choose = @$_POST['choose'];
  //Làm sạch
  $name = strip_tags($name);
  $name = addslashes($name);
  $zalo = strip_tags($zalo);
  $zalo = addslashes($zalo);
  $email = addslashes($email);
  $email = strip_tags($email);
  $choose = strip_tags($choose);
  $choose = addslashes($choose);
  $member_pass = strip_tags($member_pass);
  $member_pass = addslashes($member_pass);
  $member_user = strip_tags($member_user);
  $member_user = addslashes($member_user);

if ($name ==""||$choose=="") {
  $notice= 1;// echo 'Không được để trống.';
  }
  elseif ($email !=""&&filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
    $notice=4;// echo 'Lỗi định dạng email.';
}   
  // Ngược lại
  else {
      // Lệnh SQL đổi mật khẩu
      if($member_pass !=""){   //Mã hóa mật khẩu bằng thuật toán bcrypt
        $options = [ 'cost' => 12];
        $member_pass=password_hash($member_pass, PASSWORD_BCRYPT, $options); }
      $sql = "INSERT INTO `tblmember`(`Member_Name`, `Member_ZaloID`, `Member_Email`, `Member_Choose`,`Member_User`,`Member_Hash`) VALUES ('$name','$zalo','$email','$choose','$member_user','$member_pass')";
      // Thực hiện truy vấn
     $query= mysqli_query($conn, $sql);
 
   if($query) $notice= 0;// echo 'Đã thêm 1 nhân viên.';
      else $notice= 3;// echo 'Đã có lỗi xảy ra';
  }
}

?>


<?php  
//<!-----------code tạo form nhập----------->
            //câu lệnh sql lấy ra all thông tin của bảng department
            $sql = "SELECT * FROM `tbldepartment`" ;
            
            // thực thi câu $sql với biến conn lấy từ file connection.php
            $query = mysqli_query($conn,$sql);           
          ?>

<?php 
// 
            //câu lệnh sql lấy ra all thông tin của bảng department
            $sql = "SELECT * FROM `tblmember`" ;
            
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
            <legend>Quản trị nhân viên</legend>
<form action="member.php" method="post"  style="padding-left: 25px;">
  
    <table>
    <tr>
                        <td></td>

                        
                        <td id="notice">
                                 <?php 
                            if($notice==0) echo '<div class="alert-box success"><span>Thành công: </span>Yêu cầu đã được thực hiện.</div>';
                            elseif($notice==1) echo'<div class="alert-box warning"><span>Cảnh báo: </span>Không được để trống.</div>';
                            elseif($notice==2) echo'<div class="alert-box error"><span>Lỗi: </span>Không tồn tại cán bộ.</div>';
                            elseif($notice==3) echo'<div class="alert-box error"><span>Lỗi: </span>Đã có lỗi xảy ra</div>';
                            elseif($notice==4) echo'<div class="alert-box error"><span>Lỗi: </span>Lỗi định dạng e-mail</div>';
                         
                        ?>

                        </td>
                    </tr>
      <tr>
        <td>Họ và tên <span style="color:red;">*</span></td>
        <td><input type="text" name="name"<?php if($isUpdate ==1) echo ' value =\''.$name.'\' ';?>></td>
      </tr>
      <tr>
        <td>Zalo ID </td>
        <td><input type="text" name="zalo" <?php if($isUpdate ==1) echo ' value =\''.$zalo.'\' ' ?> ></td> 
      </tr>
      <tr>
        <td>Tên đăng nhập </td>
        <td><input type="text" name="member_user" <?php if($isUpdate ==1) echo ' value =\''.$member_user.'\' ' ?> ></td> 
      </tr>
      <tr>
        <td>Mật khẩu</td>
        <td><input type="text" name="member_pass"></td> 
      </tr>
      <tr>
        <td>Email </td>
        <td><input type="text" name="email" <?php if($isUpdate ==1) echo ' value =\''.$email.'\' ' ?> ></td>
      </tr>
      <tr>
      <td>Nhận thông báo qua </td>
        <td  colspan="2" align="center">   <span class="inline" >  <select id="nut" name="choose">   
            <option value="1"<?php if($isUpdate ==1) if($choose ==1 ) echo ' selected' ?>>Email</option>     
            <option value="2"<?php if($isUpdate ==1) if($choose ==2 ) echo ' selected' ?>>Zalo</option> 
          </select></span>
        

    <input id="nut" type="submit" <?php if ($isUpdate==0) echo 'name="btn_submit_add" value="Thêm" '; 
                                                            else echo 'name="btn_submit_update" value="Cập nhật" ';?>>
  
  
  </td>
  </tr>
  <tr>
    <td> </td>
    <td> 
<span class="inline" style="padding-top:10px; padding-left: 10px;"> <a href="zalo.php"> Tìm UID Zalo </a></span>


      <span class="inline" style="float:right; <?php if($isUpdate==0) echo ' display:none; '?>" > 
      <input id="nut" type="submit" name="btn_return" value="Quay lại"></span></td>
  </tr>
  </table>

</form>
<!-- Tạo bảng và chèn dữ liệu cho bảng dstk-->
<table  class="tabletable table-hover table-bordered results" style="margin-left: 10px; text-align: center;margin-right:10px; ">
  <caption>Danh sách cán bộ</caption>
  <thead>
    <tr style="height: 45px; background-color: #fff0ea ">
      <th class="col-md-1 col-xs-1" style="text-align: center">STT</th>
      <th class="col-md-2 col-xs-2" style="text-align: center">Họ và tên</th>
      <th class="col-md-2 col-xs-2" style="text-align: center">Zalo ID</th>
      <th class="col-md-3 col-xs-3" style="text-align: center">Email</th>
      <th class="col-md-2 col-xs-2" style="text-align: center">Tên đăng nhập</th>
      <th class="col-md-3 col-xs-3" style="text-align: center">Nhận thông báo qua</th>
      <th class="col-md-1 col-xs-1" style="text-align: center">Sửa</th>
      <th class="col-md-1 col-xs-1" style="text-align: center">Xóa</th>
    </tr>
  </thead>
  <tbody>
    <?php  
            $stt = 1 ;
            //câu lệnh sql lấy ra all thông tin của bảng admin và department
            $sql = "SELECT * FROM `tblmember`" ;
            
            // thực thi câu $sql với biến conn lấy từ file connection.php
            $query = mysqli_query($conn,$sql);
            //vòng lặp từ dòng data
           
            while ($data = mysqli_fetch_array($query)) {
          ?>
    <tr style="height: 40px;">
      <th scope="row" style="text-align: center">
        <?php echo $stt++ ?>
      </th>
      <td style="text-align: center">
        <?php echo $data["Member_Name"]; ?>
      </td>
      <td>
      <?php echo $data["Member_ZaloID"]; ?>
      </td>
      <td>
      <?php echo $data["Member_Email"]; ?>
      </td>
      <td>
      <?php echo $data["Member_User"]; ?>
      </td>
      <td>
        <?php 
                    if ($data["Member_Choose"] == 1) {
                      echo "Email";                
                      }elseif ($data["Member_Choose"] == 2) {
                        echo "Zalo";
                        }?>
      </td>
     
      <td><a href="member.php?<?php echo 'id='.$data["Member_ID"].'&acction=update'; ?>" >Sửa</a> </td>
      <td><a href="member.php?<?php echo 'id='.$data["Member_ID"].'&acction=delete'; ?>" onclick="return confirmAction()">Xóa</a> </td>
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