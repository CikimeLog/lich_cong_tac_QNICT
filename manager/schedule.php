<?php session_start();ob_start(); ?> 
 <!DOCTYPE html>
<html>

<head>
    <title>Quản lý lịch</title>



    <link rel="stylesheet" type="text/css" href="../css/3.3.6.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../css/4.17.37.bootstrap-datetimepicker.min.css">
    <script type="text/javascript" src="../js/1.12.4.jquery.min.js"></script>
    <script type="text/javascript" src="../js/2.14.1.moment.min.js"></script>
    <script type="text/javascript" src="../js/3.3.6.bootstrap.min.js"></script>
    <script type="text/javascript" src="../js/4.17.37.bootstrap-datetimepicker.min.js"></script>
 	<link rel="icon" type="image/png" href="../images/qnict.ico"/>
    <script src="../js/ckeditor/ckeditor.js"></script>
    <link rel="stylesheet" href="../css/StyleAdmin.css">
    <link rel="stylesheet" href="../fastselect/fastselect.min.css">
    <script src="../fastselect/fastselect.standalone.js"></script>
    <script type='text/javascript'>
        $(document).ready(function () {
            $('#datetimepicker1').datetimepicker();
        });
    </script>
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

.fstElement {
    font-size: 1.0em;
}

.fstToggleBtn {
    min-width: 350px;
}

.fstMultipleMode {
    display: block;
   padding:-5px;
}

.fstMultipleMode .fstControls {
    width: 100%;
}

</style>

</head>
<!--Hàm hiện thông báo xác nhận yêu cầu xóa-->
<SCRIPT LANGUAGE="JavaScript">
    function confirmAction() {
        return confirm("Bạn có chắc chắn muốn xóa?")
      }
 
</SCRIPT>
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


    $notice=-1;
    if (isset($_SESSION['search']) && isset($_GET['search']))
    if($_GET['search']==1)
    $sqlSearch=$_SESSION['search'];
    
?>
	<?php 
$json=file_get_contents('../olaz/info.json');
$json = json_decode($json, true);
$isLive= $json["zalo"]["isLive"];
if($isLive== 0){
    echo '	<script language="javascript">
        	alert(\'Token zalo đã die vui lòng lấy lại\');
        </script>';
}
?>

<?php
if (isset($_POST["btn_return"])) {
  header('Location: schedule.php');}
  ?>

    <?php
//KHI NGUOI DUNG NHAP VAO NUT tìm kiếm
if (isset($_POST["btn_search"])) {
    // Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
    
    $search=$_POST["btn_search"];
    $datetime = @($_POST['date']);
    $person = @$_POST['person'];
    $content = @$_POST['content'];

    //Làm sạch
    $datetime = strip_tags($datetime);
    $datetime = addslashes($datetime);
    $search = strip_tags($search);
    $search = addslashes($search);
    $content = strip_tags($content);
    $content = addslashes($content);
    $checkDate=1;
    try {
        
            $datetime=date_create($datetime);

            $date= date_format($datetime, "Y-m-d");
        
    } catch (Exception $e) {
  
  //lỗi ngày tháng
        $checkDate= 0;
        // echo 'lỗi thời gian';
    }
    if ($person != null && !is_array($person)) {
        $notice= 7; //thêm cái lỗi thứ 7
  
       // echo 'Lỗi người thông báo.';
    } elseif ($checkDate == 0) {
        $notice= 6;
       // echo 'Lỗi ngày tháng.';
    }
    elseif($search==1){}
    // Ngược lại
    else {
        // Lệnh SQL search
        if ($search==2) {
            $sqlSearch = " WHERE `Schedule_Date` = '$date' ";
            $_SESSION['search']=$sqlSearch;
        } elseif ($search==3) {
            $sqlSearch = " WHERE `Schedule_Content` like '%$content%' ";
            $_SESSION['search']=$sqlSearch;
        } elseif ($search==4) {
            $checkAdd=0;
            if ($person!= null) {
                foreach ($person as $data) {
                    $data = addslashes($data);
                    $data = strip_tags($data);
                    if ($checkAdd==0) {
                        $sql1 = "((SELECT `Schedule_ID` FROM `tblmember_schedule` WHERE `Member_ID`=$data))";
                        $checkAdd==1;
                    } elseif ($checkAdd==1) {
                        $sql1 = "(SELECT `Schedule_ID` FROM `tblmember_schedule` WHERE `Member_ID`=$data AND `Schedule_ID` IN".$sql.")";
                    }
                    $sqlSearch = " WHERE `Schedule_ID` IN (".$sql1.") ";

                    $_SESSION['search']=$sqlSearch;
                }
              
            }
            else{
              $sqlSearch = " WHERE `Schedule_ID` NOT IN (SELECT `Schedule_ID` FROM `tblmember_schedule`) ";
              $_SESSION['search']=$sqlSearch;
            }
        }
       if($_POST['btn_search']!=1)
        header('Location: schedule.php?search=1');
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
  $sql = "SELECT `Schedule_ID` FROM `tblschedule` WHERE `Schedule_ID` ='$id'" ;
  // thực thi câu $sql với biến conn lấy từ file connection.php
  $query = mysqli_query($conn,$sql);    
  $flag=0;
  while ($data = mysqli_fetch_array($query)) {
    if($data['Schedule_ID']!=null){$flag = 1;}
  }

    if($_GET['acction']=="delete" && $flag==1){
  // Lệnh SQL delete
  $sql = "DELETE FROM `tblmember_schedule` WHERE `Schedule_ID`='$id'";
  // Thực hiện truy vấn
  $query= mysqli_query($conn, $sql);
  $sql = "DELETE FROM `tblschedule` WHERE `Schedule_ID`='$id'";
  // Thực hiện truy vấn
  $query= mysqli_query($conn, $sql);

if($query) $notice= 0;//echo 'Đã xóa lich';
  else $notice= 3;// echo 'Đã có lỗi xảy ra'; 

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
   $sql = "SELECT * FROM `tblschedule` WHERE Schedule_ID='$id'" ;
            
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
    $date=$data["Schedule_Date"];
    $time=$data["Schedule_Time"];
    $Department_ID=$data["Schedule_DepartmentID"];
    $isShow=$data["Schedule_Show"];
    $content=$data["Schedule_Content"];
    $_SESSION['tempID']=$id;
  //câu lệnh sql lấy ra all thông tin của các member liên quan
    $sql = "SELECT `Member_ID` FROM `tblmember_schedule` WHERE `Schedule_ID`='$id'" ;
            
  // thực thi câu $sql với biến conn lấy từ file connection.php
  
  $selectedQuery= mysqli_query($conn, $sql);
  $mangID = array();
  while ($data1 = mysqli_fetch_array($selectedQuery)) {
    array_push($mangID, $data1["Member_ID"]);
  }
  }
  }
}
//KHI NGUOI DUNG NHAP VAO NUT UPDATE
if (isset($_POST["btn_submit_update"])) {
  // Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
  $datetime = @($_POST['date']);
  $Department_ID = @$_POST['Department_ID'];
  $person = @$_POST['person'];
  $content = @$_POST['content'];
  if(isset($_POST['isShow'])){
    $isShow=1;
  } else $isShow =0;

  $id= $_SESSION['tempID'];
  //Làm sạch
  $datetime = strip_tags($datetime);
  $datetime = addslashes($datetime);
  $Department_ID = strip_tags($Department_ID);
  $Department_ID = addslashes($Department_ID);
  $id = strip_tags($id);
  $id = addslashes($id);

  // $content = strip_tags($content);
  // $content = addslashes($content);

//kiểm tra trùng 
  //câu lệnh sql 
  $sql = "SELECT Schedule_ID FROM `tblschedule`" ;
  // thực thi câu $sql với biến conn lấy từ file connection.php
  $query = mysqli_query($conn,$sql);    
  $flag=0;
  while ($data = mysqli_fetch_array($query)) {
    if($data['Schedule_ID']==$id){$flag = 1;}
  }
  $checkDate=1;
try{
  if ($datetime!=null) {
      $datetime=date_create($datetime);
      $time= date_format($datetime, "H:i:s");
      $date= date_format($datetime, "Y-m-d");
  }
}catch(Exception $e){
  
  //lỗi ngày tháng
  $checkDate= 0;
 // echo 'lỗi thời gian';
}
  if ($datetime ==""||$Department_ID==""||$content=="") {
    $notice= 2;
   // echo 'Không được để trống.';
  }
  elseif ($flag == 0) {
    $notice= 5;
  //  echo 'Lỗi không tồn tại lịch de cap nhat.';
}
elseif ($person != NULL && !is_array($person)) {
  $notice= 7;// thêm cái lỗi thứ 7
  
 // echo 'Lỗi người thông báo.';
}
elseif ($checkDate == 0) {
  $notice= 6;
  //echo 'Lỗi ngày tháng.';
}
  // Ngược lại
  else {
      // Lệnh SQL update vào tblschedule
    
      $sql = "UPDATE `tblschedule` SET `Schedule_Content`='$content',`Schedule_Date`='$date',`Schedule_Time`='$time',`Schedule_DepartmentID`='$Department_ID',`Schedule_Show`='$isShow' WHERE `Schedule_ID`='$id'";
      // Thực hiện truy vấn
    $query= mysqli_query($conn, $sql);
    //lệnh sql xóa all person theo id
    $sql = "DELETE FROM `tblmember_schedule` WHERE `Schedule_ID`='$id'";
    // Thực hiện truy vấn
    $query= mysqli_query($conn, $sql);
    if ($person!= null) {
        foreach ($person as $data) {
            $data = addslashes($data);
            $data = strip_tags($data);
        
            $sql = "INSERT INTO `tblmember_schedule`(`Schedule_ID`, `Member_ID`) VALUES ('$id','$data')";
            // Thực hiện truy vấn
            $query= mysqli_query($conn, $sql);
        }
    }

  if($query)$notice= 0;// echo 'Thành công';
      else $notice= 3;// echo 'Đã có lỗi xảy ra';
  }
}

?>

    <!-----------code thêm----------->
    <?php  
if (isset($_POST["btn_submit_add"])) {
  // Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
  $datetime = @($_POST['date']);
  $Department_ID = @$_POST['Department_ID'];
  $person = @$_POST['person'];
  $content = @$_POST['content'];
  if(isset($_POST['isShow'])){
    $isShow=1;
  } else $isShow =0;
  //Làm sạch
  $datetime = strip_tags($datetime);
  $datetime = addslashes($datetime);
  $Department_ID = strip_tags($Department_ID);
  $Department_ID = addslashes($Department_ID);
  // $content = strip_tags($content);
  // $content = addslashes($content);
  $checkDate=1;
try{
  if ($datetime!=null) {
      $datetime=date_create($datetime);
      $time= date_format($datetime, "H:i:s");
      $date= date_format($datetime, "Y-m-d");
  }
}catch(Exception $e){
  
  //lỗi ngày tháng
  $checkDate= 0;
 // echo 'lỗi thời gian';
}

if ($datetime ==""||$Department_ID==""||$content=="") {
  $notice= 2;
  //echo 'Không được để trống.';
}
elseif ($person != NULL && !is_array($person)) {
  $notice= 7;// thêm cái lỗi thứ 7

  //echo 'Lỗi người thông báo.';
}
elseif ($checkDate == 0) {
$notice= 6; //cái mã notice này mới thêm tên là lỗi ngày tháng
//echo 'Lỗi ngày tháng.';
}
// Ngược lại
else {
    // Lệnh SQL insert vào tblschedule
 
    $sql = "INSERT INTO `tblschedule`(`Schedule_Content`, `Schedule_Date`, `Schedule_Time`, `Schedule_DepartmentID`, `Schedule_Show`) VALUES ('$content','$date','$time','$Department_ID','$isShow')";
    // Thực hiện truy vấn
    $query= mysqli_query($conn, $sql);
    if($query) {
        $last_id = mysqli_insert_id($conn);
  if ($person != null) {
      foreach ($person as $data) {
          $data = addslashes($data);
          $data = strip_tags($data);
      
          $sql = "INSERT INTO `tblmember_schedule`(`Schedule_ID`, `Member_ID`) VALUES ('$last_id','$data')";
          // Thực hiện truy vấn
          $query= mysqli_query($conn, $sql);
      }
    }
  }
    

if($query)$notice= 0;// echo 'Thành công';
    else $notice= 3;// echo 'Đã có lỗi xảy ra';
}
}

?>


    <?php  
            //câu lệnh sql lấy ra all thông tin của bảng department
            $sql = "SELECT * FROM `tbldepartment`" ;
            
            // thực thi câu $sql với biến conn lấy từ file connection.php
            $query = mysqli_query($conn,$sql);       
            //câu lệnh sql lấy ra all thông tin của bảng member
             $sql = "SELECT * FROM `tblmember`" ;
            // thực thi câu $sql với biến conn lấy từ file connection.php
            $query1 = mysqli_query($conn,$sql);       
          ?>




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
            <div class="animation start-home"></div>
        </div>

    </div>
    <div style="background-color: #f7fcff; min-height: 700px;">








        <fieldset>
            <legend>Quản trị lịch</legend>
            <form method="POST" action="schedule.php" style=" padding-left: 25px ">

                <table>
                <tr >
                        <td></td>

                        
                        <td id="notice" >
                                 <?php 
                            if($notice==0) echo '<div class="alert-box success" style="width:350px;"><span>Thành công: </span>Yêu cầu đã được thực hiện.</div>';
                            elseif($notice==2) echo'<div class="alert-box warning" style="width:350px;"><span>Cảnh báo: </span>Không được để trống.</div>';
                            elseif($notice==3) echo'<div class="alert-box error" style="width:350px;"><span>Lỗi: </span>Đã có lỗi xảy ra.</div>';
                            elseif($notice==5) echo'<div class="alert-box error" style="width:350px;"><span>Lỗi: </span>Không tồn tại lịch để cập nhật</div>';
                            elseif($notice==6) echo'<div class="alert-box error" style="width:350px;"><span>Lỗi: </span>Lỗi ngày tháng</div>';
                            elseif($notice==7) echo'<div class="alert-box error" style="width:350px;"><span>Lỗi: </span>Lỗi người được thông báo</div>';
                        ?>

                        </td>
                    </tr>
                    <tr>
                        <td>Thời gian <span style="color:red;">*</span></td>
                        <td>
                            <div class='input-group date' id='datetimepicker1'>
                                <input type='text' class="form-control" name="date" placeholder="Tháng/Ngày/Năm Giờ:Phút "
                                    <?php if ($isUpdate==1) { $date=date_create($date); $date=date_format($date,
                                    "m/d/Y" ); echo 'value= "' .$date.' '.$time.'"';
                            }
                                  ?>/>
                            <span class="
                                    input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
    </div>
    </td>
    </tr>
    <tr>
        <td>Thuộc Phòng, Ban</td>
        <td> <select id="Department_ID" name="Department_ID">
                <?php
        while ($data = mysqli_fetch_array($query)) {
          ?>
                <option value=<?php echo '"' .$data["Department_ID"].'"'; if($isUpdate==1 && $data["Department_ID"]==$Department_ID)
                    echo "selected" ?>>
                    <?php echo $data["Department_Name"];?>
                </option>
                <?php }?>

            </select>
        </td>
    </tr>
    <tr>
    <td style="width: 145px;">Nội dung &nbsp; <span style="color:red;">*</span></td>
        <td><?php   
      $contentAdd="";
      
             ?>   
    <textarea style="margin: 50px;" name="content" id="cuteEditor" rows="10" cols="5">
    <?php   
      $contentAdd="";
         if($isUpdate==1 ) $contentAdd=$content;
         echo $contentAdd;  

             ?> 
    </textarea>
            <script>
                CKEDITOR.replace( 'cuteEditor',{
    uiColor: '#14B8C4'} );
            </script></td>
    </tr>
    <tr>
        <td>Hiển thị</td>
        <td><input type="checkbox" id="checkbox" name="isShow"  value="1" <?php if($isUpdate==1 && $isShow==1) echo
                ' checked' ;?>></td>
    </tr>
    <tr>
            <td>Người được thông báo &nbsp; &nbsp; </td>
            <td> <select class="multipleSelect" multiple name="person[]">

                <?php
                    while ($data = mysqli_fetch_array($query1)) {
                      if ($isUpdate==1) {
                          ?>
                <option value="<?php echo $data["Member_ID"]; ?>"
                  <?php if (in_array($data["Member_ID"], $mangID))    echo "Selected ";
                          ?> >
                  <?php echo $data["Member_Name"];
                      } ?>
                </option>
                <?php if ($isUpdate==0) {
                          ?>
                <option value="<?php echo $data["Member_ID"]; ?>">
                  <?php echo $data["Member_Name"];
                      } ?>
                </option>
                <?php }?>
              </select>
              <script>
                $('.multipleSelect').fastselect();
            </script>
            </td>
          </tr>
    <tr>
    <td></td>
        <td colspan="2" align="center">

        <span class="inline"><select id="nut" name="btn_search" onchange="this.form.submit()">
                <option id="timkiem" value="1">Tìm kiếm</option>
                <option id="timkiem" value="2"><b>Tìm theo thời gian</b></option>
                <option id="timkiem" value="3">Tìm theo nội dung</option>
                <option id="timkiem" value="4">Tìm theo người thông báo</option>
               
            </select></span>
            <span class="inline">    <input id="nut" type="submit" <?php if ($isUpdate==0) echo
                'name="btn_submit_add" value="Thêm" ' ; else echo 'name="btn_submit_update" value="Cập nhật" ' ;?>>
          <button  id="return" style="float:left; <?php if($isUpdate==0 && !isset($_GET['search'])) echo ' display:none; '?>">
            <a  id="nut" href ="schedule.php">Quay lại</a></button></span>
            </td>
    </tr>
    
   
    </table>
    </form>
    </fieldset>
    <?php
    //Tạo bảng và chèn dữ liệu cho bảng + phan trang-->
    if (!isset($_SESSION["search"]) || !isset($_GET["search"]) || $_GET["search"]!= 1){
    $currentdate=date('Y-m-d');
    $sql= "select count(Schedule_ID) as total from tblschedule WHERE `Schedule_Date` >= '$currentdate'"; }
   
    else
    {
    
        $sql= "select count(Schedule_ID) as total from tblschedule ".$sqlSearch;
        
    }
  
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
        //TRUY VẤN LẤY DANH SÁCH LỊCH
        // Có limit và start rồi thì truy vấn CSDL
        $currentdate=date('Y-m-d');
        if (!isset($_SESSION["search"]) || !isset($_GET["search"]) || $_GET["search"]!= 1){
        $sql="SELECT * FROM `tblschedule` INNER JOIN  `tbldepartment` on `tblschedule`.`Schedule_DepartmentID`=`tbldepartment`.`Department_ID` WHERE `Schedule_Date` >= '$currentdate' ORDER BY `Schedule_Date` DESC LIMIT $start, $limit";
       $xxx=0;
     
         } else{
        $sql= "SELECT * FROM `tblschedule` INNER JOIN  `tbldepartment` on `tblschedule`.`Schedule_DepartmentID`=`tbldepartment`.`Department_ID` ".$sqlSearch."ORDER BY `Schedule_Date` DESC LIMIT $start, $limit";
          $xxx=1;
      }
       
        $result = mysqli_query($conn, $sql);
        ?>
    <div>
      <?php 
            // PHẦN HIỂN THỊ
            //HIỂN THỊ DANH SÁCH LỊCH
          
            ?>


      <table  class="tabletable table-hover table-bordered results" style="margin-left: 10px; text-align: center;margin-right:10px; ">
        <caption>Danh sách lịch làm việc</caption>
        <thead>
          <tr style="height: 45px; background-color: #fff0ea ">
            <th class="col-md-1 col-xs-1" style="text-align: center">STT</th>
            <th  class="col-md-2 col-xs-2" style="text-align: center">Ngày</th>
            <th  class="col-md-5 col-xs-4" style="text-align: center">Nội dung</th>
            <th  class="col-md-3 col-xs-2" style="text-align: center">Phòng, ban</th>
            <th  class="col-md-1 col-xs-1" style="text-align: center">Hiển thị</th>
            <th  class="col-md-1 col-xs-1" style="text-align: center">Sửa</th>
            <th  class="col-md-1 col-xs-1" style="text-align: center">Xóa</th>
          </tr>
        </thead>
        <tbody>
          <?php  

            $stt = ($current_page -1)*30 +1;
            if($result!=null)
            while ($data = mysqli_fetch_array($result)) {
          ?>
          <tr <?php if($isUpdate==1 && $id==$data["Schedule_ID"]) echo 'id="selected";' ?>>
            <th scope="row" style="text-align: center">
              <?php echo $stt++ ?>
            </th>
            <td style="text-align: center">
              <?php 
            $date = $data["Schedule_Date"];
            $thu=rebuild_date("l", strtotime($date));
            $date = date_create($date);

            echo $thu.'<br><b><font color="blue">'.date_format($date,"d/m/Y").'</font></b>';
            
        
        ?>
            </td>
            <td  style=" text-align: justify; padding: 5px 10px 5px 10px; ">
              <?php  $date = date_create($data["Schedule_Time"]);
            echo '<b><font color="red">'.date_format($date,"H:i").'</font></b> '; 
            echo strip_tags($data["Schedule_Content"]);?>
            </td>
            <td style="  text-align: center; padding: 0px 10px 0px 10px;">
                        <?php echo $data["Department_Name"]; ?>
                    </td>
            <td>
              <?php 
                    if ($data["Schedule_Show"] == 1) {
                      echo "Có";
                    }elseif ($data["Schedule_Show"] == 0) {
                      echo "Không";
                      } ?>
            </td>
            <td><a href="schedule.php?<?php echo 'search='.$xxx.'&page='.$current_page.'&id='.$data["Schedule_ID"].'&acction=update'; ?>" >Sửa</a> </td>
            <td><a href="schedule.php?<?php echo 'id='.$data["Schedule_ID"].'&acction=delete'; ?>" onclick="return  confirmAction()">Xóa</a> </td>
          </tr>
          <?php
            }
          ?>
        </tbody>
      </table>




    </div>
    <div class="phantrang" >
      <?php 
            // PHẦN HIỂN THỊ PHÂN TRANG
            //  HIỂN THỊ PHÂN TRANG
            // nếu current_page > 1 và total_page > 1 mới hiển thị nút prev
            if ($current_page > 1 && $total_page > 1){
                echo '<a href="schedule.php?page='.($current_page-1).'">Prev</a> | ';
            }
            // Lặp khoảng giữa
            for ($i = 1; $i <= $total_page; $i++){
                // Nếu là trang hiện tại thì hiển thị thẻ span
                // ngược lại hiển thị thẻ a
                if ($i == $current_page){
                    echo '<span>'.$i.'</span> | ';
                }
                else{
                    echo '<a href="schedule.php?page='.$i.'">'.$i.'</a> | ';
                }
            }
            // nếu current_page < $total_page và total_page > 1 mới hiển thị nút prev
            if ($current_page < $total_page && $total_page > 1){
                echo '<a href="schedule.php?page='.($current_page+1).'">Next</a> | ';
            }
          ?>
    </div>
    </div>
   
    <footer class="container-fluid text-center">
        <p>Bản quyền thuộc về Trung tâm Công nghệ thông tin và Truyền thông Quảng Ninh
        </p>
        <p>All rights reserved.</p>
    </footer>
</body>

</html>
<?php ob_end_flush(); ?>