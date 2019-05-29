<?php session_start();
ob_start(); ?> 
 <!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Thông báo</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="../css/3.4.0.bootstrap.min.css">
    <script src="../js/3.3.1.jquery.min.js"></script>
    <script src="../js/3.4.0.bootstrap.min.js"></script>
   
 	<link rel="icon" type="image/png" href="../images/qnict.ico"/>
   <script src="../js/ckeditor/ckeditor.js"></script>
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

date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once("../include/function.php");  
?>

<?php
//kiểm tra xem đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
header('Location: login.php');
}
///Gọi file connection.php
require_once("../include/connection.php");
$isUpdate=0;
$notice=-1;

?>
<?php
if (isset($_GET['id'])&&isset($_GET['acction'])) {
   $id =$_GET['id'];
   $id = strip_tags($id);
   $id = addslashes($id);
   if ($_GET['acction']=="change") {
      //câu lệnh sql lấy ra all thông tin của bảng tblschedule
        $sql = "SELECT * FROM `tblnotification` WHERE Noti_ID='$id'" ;
            
   // thực thi câu $sql với biến conn lấy từ file connection.php
   $query= mysqli_query($conn, $sql);
   $data = mysqli_fetch_array($query);

      if ($data==null) {
            die(' Không tìm thấy thông báo này.
   <button type="button" id ="nut" onclick="quay_lai_trang_truoc()">Quay lại trang trước</button>

   <script>
      function quay_lai_trang_truoc(){
            history.back();
      }
   </script>');
      } else {
         $change=$data['Noti_IsShow'];
           $change=$change*(-1);
            $sql = "UPDATE `tblnotification` SET `Noti_IsShow`='$change' WHERE `Noti_ID`='$id'";
            if (mysqli_query($conn, $sql)) {
               $notice=0;//  echo 'Thành công';
                header('Location: notice.php');
            } else {
               $notice=2;//   echo 'Thất bại.';
            }
      }
   }
}
?>
<?php
//-----------code delete-----------
if(isset($_GET['id'])&&isset($_GET['acction'])){
   $id =$_GET['id'];
   $id = strip_tags($id);
   $id = addslashes($id);
//kiểm tra user
//câu lệnh sql
$sql = "SELECT `Noti_ID` FROM `tblnotification` WHERE `Noti_ID` ='$id'" ;
// thực thi câu $sql với biến conn lấy từ file connection.php
$query = mysqli_query($conn,$sql);    
$flag=0;
while ($data = mysqli_fetch_array($query)) {  
   if($data['Noti_ID']!=null){$flag = 1;}

}

   if($_GET['acction']=="delete" && $flag==1){
$sql = "DELETE FROM `tblnotification` WHERE `Noti_ID`='$id'";
// Thực hiện truy vấn
$query= mysqli_query($conn, $sql);

if($query) $notice= 0;//echo 'Đã xóa lich';
else   $notice=3;//   echo 'Đã có lỗi xảy ra'; 

   }
}


?>
<?php
//-----------code update-----------
//cờ để xác định cập nhật hay thêm
$isUpdate=0;// thì là thêm

if(isset($_GET['id'])&&isset($_GET['acction'])){
$id =$_GET['id'];
$id = strip_tags($id);
$id = addslashes($id);
if($_GET['acction']=="update"){
$isUpdate=1; //cập nhật
//câu lệnh sql lấy ra all thông tin của bảng tblschedule
   $sql = "SELECT * FROM `tblnotification` WHERE Noti_ID='$id'" ;
            
// thực thi câu $sql với biến conn lấy từ file connection.php
$query= mysqli_query($conn, $sql);
$data = mysqli_fetch_array($query);

if($data==null){die(' Không tìm thấy lịch này.
<button type="button" id ="nut" onclick="quay_lai_trang_truoc()">Quay lại trang trước</button>

<script>
   function quay_lai_trang_truoc(){
            history.back();
      }
   </script>');}
else{
   $isShow=$data["Noti_IsShow"];
   $content=$data["Noti_Content"];
   $_SESSION['tempID']=$id;

}
}
}

//KHI NGUOI DUNG NHAP VAO NUT UPDATE
if (isset($_POST["btn_submit_update"])) {
// Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
$content = @$_POST['cuteEditor'];
if(isset($_POST['isShow'])){
   $isShow=1;
} else $isShow =-1;

$id= $_SESSION['tempID'];

//kiểm tra trùng 
//câu lệnh sql
  $sql = "SELECT * FROM `tblnotification`" ;
// thực thi câu $sql với biến conn lấy từ file connection.php
$query = mysqli_query($conn,$sql);    
$flag=0;
while ($data = mysqli_fetch_array($query)) {
   if($data['Noti_ID']==$id){$flag = 1;}
}

if ($content=="") {
   $notice= 1;
  // echo 'Không được để trống.';
}
elseif ($flag == 0) {
   $notice= 5;
  // echo 'Lỗi không tồn tại thông báo để cập nhật.';
}
// Ngược lại
else {
      // Lệnh SQL update 
      $sql = "UPDATE `tblnotification` SET `Noti_Content`='$content',`Noti_IsShow`='$isShow' WHERE `Noti_ID`='$id'";
      // Thực hiện truy vấn
   $query= mysqli_query($conn, $sql);
if($query)  $notice=0;//   echo 'Thành công';
      else   $notice=3;//   echo 'Đã có lỗi xảy ra';
}
}

?>
<?php
if (isset($_POST["btn_submit_add"])) {
   // Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
   $content = @($_POST['cuteEditor']);
   if (isset($_POST['isShow'])) {
   $isShow=1;
   } else {
      $isShow =-1;
   }

   if ($content =="") {
      $notice= 1;//echo 'Không được để trống.';
   }
   // Ngược lại
   else {
      // Lệnh SQL đổi mật khẩu
      $sql = "INSERT INTO `tblnotification`(`Noti_Content`, `Noti_IsShow`) VALUES ('$content', '$isShow')";
      // Thực hiện truy vấn
      $query= mysqli_query($conn, $sql);

      if ($query) {
            $notice= 0;
      }//echo 'Đã thêm 1 tài khoản.';
      else {
         $notice=3;//  echo 'Đã có lỗi xảy ra';
      }
   }
}



?>

<div class="top">
        <img src='../images/adminbanner1.png' style="display: block; margin-left: auto; margin-right: auto;">
    </div>
    <div id="menungang" style=" margin-bottom: 5px; ">
        <div class="container1">
            <ul class="menu">
                <li ><a  >Quản trị</a>
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
            <legend>Thông báo</legend>
   <form action="notice.php" method="post" style="margin: 10px;">







   <table>
         
         <tr style=" float:left" >
             <td></td>

             
             <td id="notice">
             <?php 
                            if($notice==0) echo '<div class="alert-box success"><span>Thành công: </span>Yêu cầu đã được thực hiện.</div>';
                            elseif($notice==1) echo'<div class="alert-box warning"><span>Cảnh báo: </span>Không được để trống.</div>';
                            elseif($notice==2) echo'<div class="alert-box warning"><span>Lỗi: </span>Thất bại</div>';
                            elseif($notice==3) echo'<div class="alert-box error"><span>Lỗi: </span>Đã có lỗi xảy ra</div>';
                            elseif($notice==5) echo'<div class="alert-box error"><span>Lỗi: </span>Lỗi không tồn tại thông báo để cập nhật.</div>';
                       
                            
                        ?>
             </td>
         </tr>
<td >
<?php   
      $contentAdd="";
      
             ?>   
    <textarea name="cuteEditor" id="cuteEditor" rows="10" cols="80">
    <?php   
      $contentAdd="";
         if($isUpdate==1 ) $contentAdd=$content;
         echo $contentAdd;  

             ?> 
    </textarea>
            <script>
                CKEDITOR.replace( 'cuteEditor',{
    uiColor: '#14B8C4'} );
            </script>
</td>
</tr>

         <tr style=" width:100%; float:left; margin-top: 5px;">
             <td style=" color:#00B14F;">&nbsp; <b>Hiển thị</b> &nbsp;</td>
             <td> <input type="checkbox" name="isShow" id="checkbox" size="30" value="1" <?php if($isUpdate==1 && $isShow==1) echo ' checked' ;?>></td>
         </tr>
         <tr style=" float:left">
             
            <td></td>
             <td  >
             <span class="inline"><input id="nut"  type="submit" <?php if ($isUpdate==0) echo 'name="btn_submit_add" value="Thêm" ' ;
              else echo 'name="btn_submit_update" value="Cập nhật" ' ;?>></span>
            <span class="inline" style="float:right; <?php if($isUpdate==0) echo ' display:none; '?>">
            <input id="nut" type="submit" name="btn_return" value="Quay lại"></span>
             </td>
             
             
         </tr>
     </table>










     

   
   

   </form>



   <?php
   $sql= 'select count(Noti_ID) as total from `tblnotification`';
   $result = mysqli_query($conn, $sql);
   $row = mysqli_fetch_assoc($result);
   $total_records = $row['total'];
   //TÌM LIMIT VÀ CURRENT_PAGE
      
   $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
   $limit = 30;
   //TÍNH TOÁN TOTAL_PAGE VÀ START
   // tổng số trang
   $total_page = ceil($total_records / $limit);
   // Giới hạn current_page trong khoảng 1 đến total_page
   if ($current_page > $total_page){
            $current_page = $total_page;
   }
   else if ($current_page < 1){
            $current_page = 1;
   }
   // Tìm Start
        $start = ($current_page - 1) * $limit;
      //TRUY VẤN LẤY DANH SÁCH page
      // Có limit và start rồi thì truy vấn CSDL
        $sql="SELECT * FROM  `tblnotification`  ORDER BY `Noti_IsShow` DESC LIMIT $start, $limit";
      $result = mysqli_query($conn, $sql);
      ?>
<div>
</fieldset>
      <?php 
            // PHẦN HIỂN THỊ
            //HIỂN THỊ DANH SÁCH THÔNG BÁO
            ?>
      <table class="tabletable table-hover table-bordered results" style="margin-left: 10px; text-align: center;margin-right:10px; ">
      <caption>Danh sách các thông báo</caption>
      <thead>
         <tr style="height: 45px; background-color: #fff0ea ">
            <th class="col-md-1 col-xs-1" style="text-align: center">STT</th>
            <th class="col-md-10 col-xs-10" style="text-align: center">Nội dung</th>
            <th class="col-md-2 col-xs-2" style="text-align: center">Hiển thị</th>
            <th class="col-md-1 col-xs-1" style="text-align: center">Sửa</th>
            <th class="col-md-1 col-xs-1" style="text-align: center">Xóa</th>
         </tr>
      </thead>
      <tbody>
         <?php  

            $stt = ($current_page -1)*30 +1;
            if($result!=null)
            while ($data = mysqli_fetch_array($result)) {
         ?>
         <tr style="height: 40px;" <?php if($isUpdate==1 && $id==$data["Noti_ID"]) echo 'id="selected";' ?>>
            <th scope="row" style="text-align: center">
            <?php echo $stt++ ?>
            </th>        
            <td style=" text-align: left; padding: 5px 10px 5px 10px; ">
                        <?php echo strip_tags($data["Noti_Content"]) ; ?> 
            </td>
            <td>
            <?php 
                  if ($data["Noti_IsShow"] == 1) {
                     echo '<a href=notice.php?page='.$current_page.'&id='.$data["Noti_ID"].'&acction=change'.'>Có</a>';
                  }elseif ($data["Noti_IsShow"] == -1) {
                     echo '<a href=notice.php?page='.$current_page.'&id='.$data["Noti_ID"].'&acction=change'.'>Không</a>';
                     } ?>
            </td>
            <td><a href="notice.php?<?php echo 'page='.$current_page.'&id='.$data["Noti_ID"].'&acction=update'; ?>" >Sửa</a> </td>
            <td><a href="notice.php?<?php echo 'id='.$data["Noti_ID"].'&acction=delete'; ?>" onclick="return confirmAction()">Xóa</a> </td>
         </tr>
         <?php
            }
         ?>
      </tbody>
      </table>




   </div>
   <div class="phantrang">
      <?php 
            // PHẦN HIỂN THỊ PHÂN TRANG
            //  HIỂN THỊ PHÂN TRANG
            // nếu current_page > 1 và total_page > 1 mới hiển thị nút prev
            if ($current_page > 1 && $total_page > 1){
               echo '<a href="notice.php?page='.($current_page-1).'">Prev</a> | ';
            }
            // Lặp khoảng giữa
            for ($i = 1; $i <= $total_page; $i++){
               // Nếu là trang hiện tại thì hiển thị thẻ span
               // ngược lại hiển thị thẻ a
               if ($i == $current_page){
                  echo '<span>'.$i.'</span> | ';
               }
               else{
                  echo '<a href="notice.php?page='.$i.'">'.$i.'</a> | ';
               }
            }
            // nếu current_page < $total_page và total_page > 1 mới hiển thị nút prev
            if ($current_page < $total_page && $total_page > 1){
               echo '<a href="notice.php?page='.($current_page+1).'">Next</a> | ';
            }
         ?>
   </div>




   <SCRIPT LANGUAGE="JavaScript">
   function confirmAction() 
   {
         return confirm("Bạn có chắc chắn muốn xóa?")
   }
</SCRIPT>
 </div>

<footer class="container-fluid text-center">
    <p>Bản quyền thuộc về Trung tâm Công nghệ thông tin và Truyền thông Quảng Ninh
    </p>
    <p>All rights reserved.</p>
</footer>

</body>

</html>
<?php ob_end_flush(); ?>