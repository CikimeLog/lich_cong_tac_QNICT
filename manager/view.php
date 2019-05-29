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
?>
<?php
if (isset($_GET['id'])&&isset($_GET['acction'])) {
    $id =$_GET['id'];
    $id = strip_tags($id);
    $id = addslashes($id);
    if ($_GET['acction']=="show") {
        //câu lệnh sql lấy ra all thông tin của bảng tblschedule
        $sql = "SELECT * FROM `tblschedule` WHERE Schedule_ID='$id'" ;
            
        // thực thi câu $sql với biến conn lấy từ file connection.php
        $query= mysqli_query($conn, $sql);
        $data = mysqli_fetch_array($query);

        if ($data==null) {
            die(' Không tìm thấy lịch này.
    <button type="button" id ="nut" onclick="quay_lai_trang_truoc()">Quay lại trang trước</button>

    <script>
        function quay_lai_trang_truoc(){
            history.back();
        }
    </script>');
        } else {
            $sql = "UPDATE `tblschedule` SET `Schedule_Show`='1' WHERE `Schedule_ID`='$id'";
            if (mysqli_query($conn, $sql)) {
                $notice= 0;// echo 'Thành công';
            } else {
                $notice= 1;// echo 'Thất bại.';
            }
        }
    }
}
?>
<?php
if (isset($_GET['id'])&&isset($_GET['acction'])) {
    $id =$_GET['id'];
    $id = strip_tags($id);
    $id = addslashes($id);
    if ($_GET['acction']=="hide") {
        //câu lệnh sql lấy ra all thông tin của bảng tblschedule
        $sql = "SELECT * FROM `tblschedule` WHERE Schedule_ID='$id'" ;
            
        // thực thi câu $sql với biến conn lấy từ file connection.php
        $query= mysqli_query($conn, $sql);
        $data = mysqli_fetch_array($query);

        if ($data==null) {
            die(' Không tìm thấy lịch này.
    <button type="button" id ="nut" onclick="quay_lai_trang_truoc()">Quay lại trang trước</button>

    <script>
        function quay_lai_trang_truoc(){
            history.back();
        }
    </script>');
        } else {
            $sql = "UPDATE `tblschedule` SET `Schedule_Show`='0' WHERE `Schedule_ID`='$id'";
            if (mysqli_query($conn, $sql)) {
                $notice= 0;// echo 'Thành công';
            } else {
                $notice= 1;// echo 'Thất bại.';
            }
        }
    }
}
?>
<?php
 $currentdate=date('Y-m-d');
    $sql= "select count(Schedule_ID) as total from tblschedule WHERE `Schedule_Date` >= '$currentdate'";

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
        $sql="SELECT * FROM `tblschedule` INNER JOIN  `tbldepartment` on `tblschedule`.`Schedule_DepartmentID`=`tbldepartment`.`Department_ID` WHERE `Schedule_Date` >= '$currentdate' ORDER BY `Schedule_Date` ASC LIMIT $start, $limit";
        $result = mysqli_query($conn, $sql);
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

    <?php 
            // PHẦN HIỂN THỊ
            //HIỂN THỊ DANH SÁCH LỊCH
        
            ?>

<fieldset>
            <legend>Lịch cơ quan</legend>
              
            
                                 <?php 
                            if($notice==0) echo '<div  id="notice" class="alert-box success"><span>Thành công: </span>Yêu cầu đã được thực hiện.</div>';
                         
                            elseif($notice==1) echo'<div  id="notice" class="alert-box warning"><span>Thất bại: </span>Đã có lỗi xảy ra.</div>';
                        ?>

                   
    <table  class="tabletable table-hover table-bordered results" style="margin-left: 10px; text-align: center;margin-right:10px; ">
    
                    

                      
                   
        <caption>Danh sách lịch làm việc</caption>
        
        <thead>
        <tr style="height: 45px; background-color: #fff0ea ">
            <th class="col-md-1 col-xs-1" class="col-md-1 col-xs-1" style="text-align: center">STT</th>
            <th class="col-md-2 col-xs-2" style="text-align: center">Ngày</th>
            <th class="col-md-4 col-xs-4" style="text-align: center">Nội dung</th>
            <th class="col-md-2 col-xs-2" style="text-align: center">Phòng, ban</th>
            <th class="col-md-1 col-xs-1" style="text-align: center">Hiển thị</th>
            <th class="col-md-1 col-xs-1" style="text-align: center">Hiện</th>
            <th class="col-md-1 col-xs-1" style="text-align: center">Ẩn</th>
        </tr>
        </thead>
        <tbody>
        <?php  

            $stt = ($current_page -1)*30 +1;
            if($result!=null)
            while ($data = mysqli_fetch_array($result)) {
        ?>
        <tr >
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
            <td><a href="view.php?<?php echo 'page='.$current_page.'&id='.$data["Schedule_ID"].'&acction=show'; ?>" >Hiện</a> </td>
            <td><a href="view.php?<?php echo 'page='.$current_page.'&id='.$data["Schedule_ID"].'&acction=hide'; ?>" >Ẩn</a> </td>
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
                    echo '<a href="view.php?page='.$i.'">'.$i.'</a> | ';
                }
            }
            // nếu current_page < $total_page và total_page > 1 mới hiển thị nút prev
            if ($current_page < $total_page && $total_page > 1){
                echo '<a href="view.php?page='.($current_page+1).'">Next</a> | ';
            }
        ?>
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