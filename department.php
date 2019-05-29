<?php session_start();ob_start(); ?> 
 
<!DOCTYPE html>
<html>

<head>
<title>Lịch phòng ban</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" href="css/3.4.0.bootstrap.min.css">
   <link rel="stylesheet" href="css/stylesmenu.css">
	<link rel="icon" type="image/png" href="images/qnict.ico"/>
   <script src="js/jquery-latest.min.js" type="text/javascript"></script>
   <script src="js/script.js"></script>
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
      .row.content {
        min-height: 450px;
          
        }
       /* Remove the navbar's default margin-bottom and rounded borders */
       .navbar {
         
            margin-bottom: 0;
            border-radius: 0;
        }

      /* Set gray background color and 100% height */
      .sidenav {
        min-height: 450px;
            background-color: #f1f1f1;
            height: 100%;
        }
 /* On small screens, set height to 'auto' for sidenav and grid */
 @media screen and (max-width: 800px) {
            .sidenav {
                height: auto;
               
            }

            .row.content {
                height: auto;
            }
        }

        footer {
       
    background-color: #1C86EE;
    padding: 1px;
    text-align: center;
    color: white;
  width: 100%;
max-width: 1200px;
margin-top: 10px;
        }
    </style>

</head>
<?php 
$checklogin =0;
if(isset($_SESSION["Member_ID"]))
$checklogin=1;

?>
<?php

date_default_timezone_set('Asia/Ho_Chi_Minh');
require_once("include/function.php");  
?>
<?php
///Gọi file connection.php
require_once("include/connection.php");
?>
<?php
unset($_SESSION['department_name']);
if(isset($_GET['id'])){
    $id= $_GET['id'];
    $id = strip_tags($id);
    $id = addslashes($id);
//câu lệnh sql lấy ra all thông tin của bảng tbldepartment
$sql = "SELECT * FROM `tbldepartment` WHERE Department_ID='$id'" ;
            
// thực thi câu $sql với biến conn lấy từ file connection.php
$query= mysqli_query($conn, $sql);
$data = mysqli_fetch_array($query);
if ($data==null) {
    die(' Không tìm thấy phòng ban này này.
<button type="button" id ="nut" onclick="quay_lai_trang_truoc()">Quay lại trang trước</button>

<script>
function quay_lai_trang_truoc(){
    history.back();
}
</script>');

}else{
$_SESSION['department_name']=$data['Department_Name'];
}

}
else{
  $sql = "SELECT * FROM `tbldepartment`" ;
            
// thực thi câu $sql với biến conn lấy từ file connection.php
$query= mysqli_query($conn, $sql);
$data = mysqli_fetch_array($query);
$id=$data['Department_ID'];
header("Location: department.php?id=$id");
}
?>
<body>
  <div class="container">
    <div class="top">
      <img src='images/banner.png' style="display: block; margin-left: auto; margin-right: auto; width: 100%; height: 150px;">
      <?php  
            //câu lệnh sql lấy ra all thông tin của bảng department
            $sql = "SELECT * FROM `tbldepartment` where Department_ID != '1' " ;
            
            // thực thi câu $sql với biến conn lấy từ file connection.php
            $query = mysqli_query($conn,$sql);           
          ?>

      <div id='cssmenu'>
<ul>
   <li><a href='index.php'>Lịch công tác</a></li>
   <li  class='active' ><a href='department.php'>Các phòng ban</a>
      <ul>
      <?php
        while ($data = mysqli_fetch_array($query)) {
          ?>
         <li><a href="department.php?id=<?php echo $data["Department_ID"];?>"><?php echo $data["Department_Name"];?></a></li>  <?php }?>
         
        
      </ul>
      </li>
      <li><a href='http://doc.qnict.vn/'>Tài liệu</a></li>
   <li><a href='http://congchuc.quangninh.gov.vn'>QLVB</a></li>
   <li><a href='http://mail.quangninh.gov.vn'>Thư điện tử</a></li>
   <li><a href='search.php'>Tìm kiếm</a></li>
   <li><a href='manager/login.php'>Đăng nhập quản trị</a></li>
   <li style=" <?php if ($checklogin ==0) echo 'display:none;'?>">
            <a href ="manager/logout.php">Đăng xuất</a></li>
</ul>
</div>




      <div class="container-fluid">



        <div class="row" style="background-color: antiquewhite; padding-top:5px;  color:red;  " id="ngayle">
         <?php 
           $currentdate=date('Y-m-d');
         $sql = "SELECT * FROM `tblevent`  ";
     $query = mysqli_query($conn, $sql);
     $event="";
    $date2=date('m-d');
     while ($data = mysqli_fetch_array($query)) {
       $xx=$data['Event_Date'];
         if($data['Event_ID']!= null && $data['Event_TitleName']!=null && $date2==date('m-d',strtotime("$xx")))
         $event=$event.$data['Event_TitleName']."   ";
     } if($event!= "")echo "<marquee> ⚛︎ $event ⚛︎ </marquee>" ?> 
        </div>
        <div class="row content">
         




            




          <div class="col-sm-8"  style="padding:15px;" >

            <table style=" margin:10px; ">
          
              <?php 
              if($checklogin==1){
               $currentdate=date('Y-m-d');
               $sql="SELECT * FROM `tblschedule` WHERE `Schedule_Date` = '$currentdate' AND `Schedule_Show`='1' AND `Schedule_DepartmentID`= '$id' ORDER BY `Schedule_Date`,`Schedule_Time`";
               $result = mysqli_query($conn, $sql);
               $date = $data["Schedule_Date"];
               $thu=rebuild_date("l", strtotime($date));
               $date = date_create($date);
               echo '<center>
               <font color="#0033FF" size="4"><b>
                    '. $_SESSION['department_name'].'
              
                    </b>
                    </font>
                    </center>'; 
              echo '<center>
              <font color="#0033FF" size="5"><b>
              Hôm nay: 
                   '.$thu.', ngày '.date_format($date,"d/m/Y").'
              
              </b>
              </font>
              </center>'; 
              while ($data = mysqli_fetch_array($result)) {
                $date = date_create($data["Schedule_Time"]);
                $content = $data["Schedule_Content"];
                
                $content = substr_replace($content, '<p id= "noidung" ', 0, 2);    
                echo '<tr><td  style="display: inline;    text-align: justify; float:left " >  <b><span id="gio">'.date_format($date,"H:i").':</span></b>'; 
                echo '<font id="noidung" style=" margin:0px 10px 0 10px;" >'.$content.'</font>   </td></tr>';
              
              }} else echo '<form style=" width: 500px;height:250px;">
              <table>
                  <tr >
                      <td style="padding-left: 10px; font-size: 15px; width:70%; "> <br>  Bạn cần <a href="login.php" style=" text-decoration: none;"><b>đăng nhập</b> </a> để xem được lịch công tác.
                         <p>
                          Nếu chưa có tài khoản, hãy liên hệ Quản trị phần mềm Lịch công tác tại phòng Hành chính tổng hợp để được hỗ trợ.</p>
						   <p>
                          Đ/c: Phạm Thị Trang.</p>
                          <p>Email: phamthitrang@quangninh.gov.vn<br>SĐT: 0834130448</p>
                      </td>
                      <td><img style="display: block; margin-left: auto; margin-right: auto; width: 150px;height:150px;margin-left:-10px  "src="images/lock.png "></td>
                  </tr>
              </table>
          </form>';
              ?>
              
            </table>

            <hr>
            <p  style="text-align: center; color:#02B351;">
          <b >  ✎ THÔNG BÁO</b></p>

            <?php $sql = "SELECT * FROM `tblnotification` WHERE `Noti_IsShow` = '1'";
     $query = mysqli_query($conn, $sql);
     $notice="" ; 
     while ($data = mysqli_fetch_array($query)) { $notice =$notice.$data['Noti_Content']; } echo  $notice  ?> 

     
          </div>







          <div class="col-sm-4 sidenav" style="background-color: #def0f8">



          <table style="margin-top:-20px ;margin-bottom:10px;">

          <?php 
          if($checklogin ==1){
      $dateint = mktime(0, 0, 0, date('m'), date('d')+14,date('Y'));
      $endDate= date('Y-m-d', $dateint);
      $currentdate=date('Y-m-d');
      $sql  = "SELECT * FROM `tblschedule` WHERE `Schedule_Date` > '$currentdate' AND `Schedule_Show`= '1' AND`Schedule_Date` <= '$endDate'  AND `Schedule_DepartmentID`= '$id' ORDER BY `Schedule_Date`,`Schedule_Time`";
      
     $query = mysqli_query($conn, $sql);
     $date1="";
     while ($data = mysqli_fetch_array($query)) {
      if($date1==""|| $date1 != $data['Schedule_Date'] ){
         $date1 = $data["Schedule_Date"];
        $date = $data["Schedule_Date"];
        $thu=rebuild_date("l", strtotime($date));
        $date = date_create($date);
      

    ?>
<tr>
 
    <?php  echo '<td id="thu"> <br>'.$thu.', ngày '.date_format($date,"d/m/Y").': </td> ' ;}  ?>
 


</tr>
<tr>
<?php   $date = date_create($data["Schedule_Time"]);
  $content = $data["Schedule_Content"];
                
  $content = substr_replace($content, '<p id= "noidung" ', 0, 2);    
      echo ' <td  style="display: inline;    text-align: justify; float:left" >  <b><span id="gio">'.date_format($date,"H:i").':</span></b>'; ?> <?php  
        echo '<font id="noidung" >'.$content.'</font>   </td>';
                
?>
      
</tr>
       <?php }} else echo '<br>Bạn cần <a  href= "login.php">đăng nhập </a> để xem được lịch công tác';?>
</table>






          </div>
        </div>
      </div>


      <!-- Load jquery trước khi load bootstrap js -->
      <script src="js/jquery-3.3.1.min.js"></script>
      <script src="bootstrap/js/bootstrap.min.js"></script>
    </div>

    <footer class="container-fluid text-center">
      <p>Bản quyền thuộc về Trung tâm Công nghệ thông tin và Truyền thông Quảng Ninh
      </p>
      <p>All rights reserved.</p>
    </footer>
  </div>
</body>

</html>

<?php ob_end_flush(); ?>