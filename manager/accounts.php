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
 require_once("../include/connection.php");
    $notice=-1;
?>
<!-----------code delete----------->
<?php
  if(isset($_GET['username'])&&isset($_GET['acction'])){
    $username =$_GET['username'];
    $username = strip_tags($username);
    $username = addslashes($username);
//kiểm tra user
  //câu lệnh sql lấy ra user của bảng admin
  $sql = "SELECT username FROM `tbladmin`" ;
  // thực thi câu $sql với biến conn lấy từ file connection.php
  $query = mysqli_query($conn,$sql);    
  $flag=0;
  while ($data = mysqli_fetch_array($query)) {
    if($data['username']==$username){$flag = 1;}
  }

    if($_GET['acction']=="delete" && $flag==1){
  // Lệnh SQL delete
      
  $sql = "DELETE FROM `tbladmin` WHERE `username`='$username'";
  // Thực hiện truy vấn
 $query= mysqli_query($conn, $sql);

if($query) $notice= 0;//echo 'Đã xóa tài khoản: '.$username;
  else $notice= 3;// echo 'Đã có lỗi xảy ra'; 

    }}


?>
<!-----------code update----------->
<?php
//cờ để xác định cập nhật hay thêm
$isUpdate=0;// thì là thêm
if(isset($_GET['username'])&&isset($_GET['acction'])){
  $username =$_GET['username'];
  $username = strip_tags($username);
  $username = addslashes($username);
  if($_GET['acction']=="update"){
  $isUpdate=1; //cập nhật
   //câu lệnh sql lấy ra all thông tin của bảng department
   $sql = "SELECT * FROM `tbladmin` WHERE username='$username'" ;
            
   // thực thi câu $sql với biến conn lấy từ file connection.php
   $query= mysqli_query($conn, $sql);
   $data = mysqli_fetch_array($query);

   if($data==null){die(' Không tìm thấy tài khoản này.
    <button type="button" id ="nut" onclick="quay_lai_trang_truoc()">Quay lại trang trước</button>
  
    <script>
        function quay_lai_trang_truoc(){
            history.back();
        }
    </script>');}
   else{
    $name=$data["name"];
    $auth=$data["auth"];
    $idPhong=$data["id_Department"];
   }
  }
}

if (isset($_POST["btn_submit_update"])) {
  // Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
  $name = @($_POST['name']);
  $Department_ID = @$_POST['Department_ID'];
  $username = @$_POST['username'];
  $password = @$_POST['password'];
  $renew_pass = @$_POST['renew_pass'];
  if(isset($_POST['auth'])){
    $auth=1;
  } else $auth =0;
  //Làm sạch
  $password = strip_tags($password);
  $password = addslashes($password);
  $username = strip_tags($username);
  $username = addslashes($username);
  $Department_ID = addslashes($Department_ID);
  $Department_ID = strip_tags($Department_ID);
  $renew_pass = strip_tags($renew_pass);
  $renew_pass = addslashes($renew_pass);
  $name = strip_tags($name);
  $name = addslashes($name);

 //kiểm tra trùng user
  //câu lệnh sql lấy ra user của bảng admin
  $sql = "SELECT username FROM `tbladmin`" ;
  // thực thi câu $sql với biến conn lấy từ file connection.php
  $query = mysqli_query($conn,$sql);    
  $flag=0;
  while ($data = mysqli_fetch_array($query)) {
    if($data['username']==$username){$flag = 1;}
  }

  if  ($password ==""||$username==""||$renew_pass==""||$name=="") {
    $notice= 1;// echo 'Không được để trống.';
 }
  elseif(strlen($password) < 6) {
    $notice= 2;  //echo 'Mật khẩu phải lớn hơn hoặc bằng 6 ký tự.';
}
  
  elseif ($flag == 0) {
     $notice= 5;// echo 'Lỗi không tồn tại username.';
}
  elseif ($password!=$renew_pass) {
      $notice= 4;//echo 'Mật khẩu nhập lại không khớp.';
}
  // Ngược lại
  else {

//Mã hóa mật khẩu bằng thuật toán bcrypt
      $options = [ 'cost' => 12];
      $password=password_hash($password, PASSWORD_BCRYPT, $options);

      // Lệnh SQL update
      
      $sql = "UPDATE `tbladmin` SET `hash`='$password',`auth`='$auth',`name`='$name',`id_Department`='$Department_ID' WHERE `username`='$username'";
      // Thực hiện truy vấn
     $query= mysqli_query($conn, $sql);

   if($query)  $notice= 0;// echo 'Đã cập nhật tài khoản: '.$username;
      else $notice= 6;//echo 'Đã có lỗi xảy ra';
  }
}

?>

<!-----------code thêm----------->
<?php  
if (isset($_POST["btn_submit_add"])) {
  // Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
  $name = @($_POST['name']);
  $Department_ID = @$_POST['Department_ID'];
  $username = @$_POST['username'];
  $password = @$_POST['password'];
  $renew_pass = @$_POST['renew_pass'];
  if(isset($_POST['auth'])){
    $auth=1;
  } else $auth =0;
  //Làm sạch
  $password = strip_tags($password);
  $password = addslashes($password);
  $username = strip_tags($username);
  $username = addslashes($username);
  $Department_ID = addslashes($Department_ID);
  $Department_ID = strip_tags($Department_ID);
  $renew_pass = strip_tags($renew_pass);
  $renew_pass = addslashes($renew_pass);
  $name = strip_tags($name);
  $name = addslashes($name);
 //kiểm tra trùng user
  //câu lệnh sql lấy ra user của bảng admin
  $sql = "SELECT username FROM `tbladmin`" ;
  // thực thi câu $sql với biến conn lấy từ file connection.php
  $query = mysqli_query($conn,$sql);    
  $flag=0;
  while ($data = mysqli_fetch_array($query)) {
    if($data['username']==$username){$flag = 1;}
  }

  if ($password ==""||$username==""||$renew_pass==""||$name=="") {
    $notice= 1;//echo 'Không được để trống.';
}
  elseif(strlen($password) < 6) {
    $notice= 2;  //echo 'Mật khẩu phải lớn hơn hoặc bằng 6 ký tự.';
}
  
  
  elseif ($flag == 1) {
    $notice= 3;//echo 'Tên đăng nhập đã tồn tại.';
}
  elseif ($password!=$renew_pass) {
    $notice= 4;//echo 'Mật khẩu nhập lại không khớp.';
}
  // Ngược lại
  else {

//Mã hóa mật khẩu bằng thuật toán bcrypt
      $options = [ 'cost' => 12];
      $password=password_hash($password, PASSWORD_BCRYPT, $options);

      // Lệnh SQL đổi mật khẩu
      $sql = "INSERT INTO `tbladmin`(`username`, `hash`, `auth`, `name`, `id_Department`) VALUES ('$username','$password','$auth','$name','$Department_ID')";
      // Thực hiện truy vấn
     $query= mysqli_query($conn, $sql);
 
   if($query) $notice= 0;//echo 'Đã thêm 1 tài khoản.';
      else echo 'Đã có lỗi xảy ra';
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

        <!-----------code tạo form nhập----------->
        <fieldset>
            <legend>Quản trị tài khoản</legend>
            <form action="accounts.php" method="post" style="padding-left: 25px;">

                <table>
         
                    <tr>
                        <td></td>

                        
                        <td id="notice">
                                 <?php 
                            if($notice==0) echo '<div class="alert-box success"><span>Thành công: </span>Yêu cầu đã được thực hiện.</div>';
                            elseif($notice==1) echo'<div class="alert-box warning"><span>Cảnh báo: </span>Không được để trống.</div>';
                            elseif($notice==2) echo'<div class="alert-box warning"><span>Cảnh báo: </span>Mật khẩu phải lớn hơn hoặc bằng 6 ký tự.</div>';
                            elseif($notice==5) echo'<div class="alert-box error"><span>Lỗi: </span>Tài khoản không tồn tại.</div>';
                            elseif($notice==4) echo'<div class="alert-box warning"><span>Cảnh báo: </span>Mật khẩu nhập lại không khớp.</div>';
                             elseif($notice==3) echo'<div class="alert-box warning"><span>Cảnh báo: </span>Tên đăng nhập đã tồn tại.</div>';
                             elseif($notice==6) echo'<div class="alert-box error"><span>Lỗi: </span>Đã có lỗi xảy ra</div>';
                        ?>

                        </td>
                    </tr>
                    <tr>
                        <td>Họ và tên<span style="color:red;">*</span></td>
                        <td><input type="text" name="name" <?php if($isUpdate==1) echo ' value =\''.$name.' \' ';?>></td>
      </tr>

      <tr>
        <td>Thuộc Phòng, Ban</td>
        <td> <select id="Department_ID" name="Department_ID">
            <?php
        while ($data = mysqli_fetch_array($query)) {
          ?>
            <option value=<?php echo '"'.$data["Department_ID"].'"'; if($isUpdate == 1 && $data["Department_ID"] == $idPhong) echo " selected" ?>>
              <?php echo $data["Department_Name"];?>
            </option>
            <?php }?>
          </select>
        </td>
      </tr>
      <tr>
        <td>Tài khoản<span style="color:red;">*</span></td>
        <td><input  type="text" name="username" <?php if($isUpdate ==1) echo ' value=\''.$username.'\' style="background-color: #edf7e8;" readonly' ?> ></td>
                    </tr>
                    <tr>
                        <td>Mật khẩu<span style="color:red;">*</span></td>
                        <td><input type="password" name="password"> </td>

                    </tr>
                    <tr>
                        <td>Nhập lại mật khẩu<span style="color:red;">*</span></td>
                        <td><input type="password" name="renew_pass"></td>
                    </tr>
                    <tr>
                        <td>Quản trị toàn bộ</td>
                        <td><input type="checkbox" id="checkbox" name="auth" value="1" <?php if($isUpdate==1 && $auth==1) echo ' checked' ;?>></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="2" align="center">
                            <span class="inline"> <input id="nut" type="submit" <?php if ($isUpdate==0) echo 'name="btn_submit_add" value="Thêm" ' ; else echo 'name="btn_submit_update" value="Cập nhật" ' ;?>></span>
                            <span class="inline" style="float:right; <?php if($isUpdate==0) echo ' display:none; '?>" > <input id="nut" type="submit" name="btn_return" value="Quay lại"></span>
                        </td>
                        
                        
                    </tr>
                </table>

            </form>
        </fieldset>
        <!-- Tạo bảng và chèn dữ liệu cho bảng dstk-->
        <table class="tabletable table-hover table-bordered results" style="margin-left: 10px; text-align: center;margin-right:10px; ">
            <caption>
                Danh sách tài khoản


            </caption>
            <thead>
                <tr style="height: 45px; background-color: #fff0ea ">
                    <th class="col-md-1 col-xs-1" style="text-align: center">STT</th>
                    <th class="col-md-3 col-xs-3" style="text-align: center">Họ và tên</th>
                    <th class="col-md-2 col-xs-2" style="text-align: center">Tên đăng nhập</th>
                    <th class="col-md-4 col-xs-4" style="text-align: center">Phòng ban</th>
                    <th class="col-md-2 col-xs-2" style="text-align: center">Quản trị</th>
                    <th class="col-md-1 col-xs-1" style="text-align: center">Sửa</th>
                    <th class="col-md-1 col-xs-1" style="text-align: center">Xóa</th>
                </tr>
               
            </thead>
            <tbody>
                <?php  
            $stt = 1 ;
            //câu lệnh sql lấy ra all thông tin của bảng admin và department
            $sql = "SELECT * FROM `tbladmin` INNER JOIN `tbldepartment` ON tbladmin.id_Department= tbldepartment.Department_ID" ;
            
            // thực thi câu $sql với biến conn lấy từ file connection.php
            $query = mysqli_query($conn,$sql);
            //vòng lặp từ dòng data
           
            while ($data = mysqli_fetch_array($query)) {
          ?>
                <tr style="height: 40px;" <?php if($isUpdate==1 && $username==$data["username"]) echo 'id="selected";' ?>>
                    <th scope="row" style="text-align: center">
                        <?php echo $stt++ ?>
                    </th>
                    <td style="text-align: center">
                        <?php echo $data["name"]; ?>
                    </td>
                    <td style="text-align: center">
                        <?php echo $data["username"]; ?>
                    </td>
                    <td>
                        <?php echo $data["Department_Name"]; ?>
                    </td>
                    <td>
                        <?php 
                    if ($data["auth"] == 1) {
                      echo "Có";
                    }elseif ($data["auth"] == 0) {
                      echo "Không";
                      }?>
                    </td>

                    <td><a href="accounts.php?<?php echo 'username='.$data["username"].'&acction=update'; ?>" >Sửa</a> </td>
                    <td><a href="accounts.php?<?php echo 'username='.$data["username"].'&acction=delete'; ?>" onclick="return confirmAction()">Xóa</a> </td>
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