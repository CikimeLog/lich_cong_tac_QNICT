<?php
session_start(); ob_start();?> 
 <!DOCTYPE html>
<html>

<head>
  <title>Quản lý sự kiện</title>

  <link rel="stylesheet"  href="../css/3.3.6.bootstrap.min.css">
  <link rel="stylesheet"  href="../css/4.17.37.bootstrap-datetimepicker.min.css">
  <script type="text/javascript" src="../js/1.12.4.jquery.min.js"></script>
  <script type="text/javascript" src="../js/2.14.1.moment.min.js"></script>
  <script type="text/javascript" src="../js/3.3.6.bootstrap.min.js"></script>
 	<link rel="icon" type="image/png" href="../images/qnict.ico"/>
  <script type="text/javascript" src="../js/4.17.37.bootstrap-datetimepicker.min.js"></script>
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
 <script type="text/javascript">
            $(function () {
                $('#datetimepicker3').datetimepicker({
                    format: 'MM/DD/YYYY'
                });
            });
        </script>
<?php
//kiểm tra xem đã đăng nhập chưa
if (!isset($_SESSION['username'])) {
  header('Location: login.php');
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
  //câu lệnh sql lấy ra id của bảng tblevent
  $sql = "SELECT Event_ID  FROM `tblevent`" ;
  // thực thi câu $sql với biến conn lấy từ file connection.php
  $query = mysqli_query($conn,$sql);    
  $flag=0;
  while ($data = mysqli_fetch_array($query)) {
    if($data['Event_ID']==$id){$flag = 1;}
  }

    if($_GET['acction']=="delete" && $flag==1){
  // Lệnh SQL delete
      
  $sql = "DELETE FROM `tblevent` WHERE `Event_ID`='$id'";
  // Thực hiện truy vấn
 $query= mysqli_query($conn, $sql);

if($query) $notice= 0;// echo 'Đã xóa thành công ngày lễ';
  else $notice= 3;// echo 'Đã có lỗi xảy ra'; 

    }}


?>

<!-----------code update----------->
<?php
//cờ để xác định cập nhật hay thêm
$isUpdate=0;// thì là thêm
if (isset($_GET['id'])&&isset($_GET['acction'])) {
    $id =$_GET['id'];
    $id = strip_tags($id);
    $id = addslashes($id);
    if ($_GET['acction']=="update") {
        $isUpdate=1; //cập nhật
        //câu lệnh sql lấy ra  thông tin của bảng event
        $sql = "SELECT * FROM `tblevent` WHERE `Event_ID`='$id'" ;
           
        // thực thi câu $sql với biến conn lấy từ file connection.php
        $query= mysqli_query($conn, $sql);
        $data = mysqli_fetch_array($query);

        if ($data==null) {
            die(' Không tìm thấy ngày lễ này.
    <button type="button" onclick="quay_lai_trang_truoc()">Quay lại trang trước</button>
  
    <script>
        function quay_lai_trang_truoc(){
            history.back();
        }
    </script>');
        } else {
            $content=$data["Event_TitleName"];
            $date=$data["Event_Date"];
            $_SESSION['id']= $id;
        }
    }
}

if (isset($_POST["btn_submit_update"])) {
$id=$_SESSION['id'];
  // Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
  $content= @($_POST['content']);
  $date = @($_POST['date']);
  //Làm sạch
  $content= strip_tags($content);
  $content= addslashes($content);
  $date = strip_tags($date);
  $date = addslashes($date);
  $checkDate=1;
  try{
    if ($date!=null) {
        $date=date_create($date);
        $date1= date_format($date, "Y-m-d");
    }
  }catch(Exception $e){
    
    //lỗi ngày tháng
    $checkDate= 0;
    $notice= 2;// echo 'lỗi thời gian';
  }
 if ($content=="" || $date="") {
  $notice= 1; // echo 'Không được để trống.';
  }elseif ($checkDate == 0) {
   $notice= 6; //cái mã notice này mới thêm tên là lỗi ngày tháng
    //echo 'Lỗi ngày tháng.';
    }
  // Ngược lại
  else {

      // Lệnh sql
      $sql = "UPDATE `tblevent` SET `Event_TitleName`='$content',`Event_Date`='$date1' WHERE `Event_ID`='$id'";
      // Thực hiện truy vấn
     $query= mysqli_query($conn, $sql);
 
   if($query)$notice= 0;// echo 'Thành công.';
      else $notice= 3;// echo 'Đã có lỗi xảy ra';
  }
}


?>

<!-----------code thêm----------->
<?php  
if (isset($_POST["btn_submit_add"])) {
  // Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
  $content = @$_POST['content'];
  $date = @($_POST['date']);
  //Làm sạch
  $content= strip_tags($content);
  $content= addslashes($content);
  $date = strip_tags($date);
  $date = addslashes($date);
  $checkDate=1;
  try{
    if ($date!=null) {
        $date=date_create($date);
        $date1= date_format($date, "Y-m-d");
    }
  }catch(Exception $e){
    
    $notice= 6;////lỗi ngày tháng
    $checkDate= 0;
    $notice= 2; // echo 'lỗi thời gian';
  }
 if ($content=="" || $date="") {
  $notice= 1;//  echo 'Không được để trống.';
  }elseif ($checkDate == 0) {
    $notice= 6; //cái mã notice này mới thêm tên là lỗi ngày tháng
    //echo 'Lỗi ngày tháng.';
    }
  // Ngược lại
  else {
      // Lệnh sql
      $sql = "INSERT INTO `tblevent`(`Event_TitleName`, `Event_Date`) VALUES ('$content','$date1')";
      // Thực hiện truy vấn
     $query= mysqli_query($conn, $sql);
 
   if($query)$notice= 0;// echo 'Thành công.';
      else $notice= 3;//echo 'Đã có lỗi xảy ra';
  }
}

?>


<?php  
            //câu lệnh sql lấy ra all thông tin của bảng event
            $sql = "SELECT * FROM `tblevent`" ;
            
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





<!----------- tạo form nhập----------->



<fieldset>
        <legend>Quản lý ngày lễ</legend>
<form method="POST" action="event.php" style="padding-left: 25px;" >

      
        <table>
        <tr>
                        <td></td>

                        
                        <td id="notice">
                                 <?php 
                            if($notice==0) echo '<div class="alert-box success"><span>Thành công: </span>Yêu cầu đã được thực hiện.</div>';
                            elseif($notice==1) echo'<div class="alert-box warning"><span>Cảnh báo: </span>Không được để trống.</div>';
                            elseif($notice==2)echo'<div class="alert-box error"><span>Lỗi: </span>Lỗi thời gian</div>';
                            elseif($notice==3) echo'<div class="alert-box error"><span>Lỗi: </span>Đã có lỗi xảy ra.</div>';
                             elseif($notice==6) echo'<div class="alert-box error"><span>Lỗi: </span>Lỗi ngày tháng</div>';
                        ?>

                        </td>
                    </tr>
          <tr>
            <td>Thời gian <span style="color:red;">*</span></td>
            <td>
              <div class='input-group date' id='datetimepicker3'>
                <input type='text' class="form-control" name="date" placeholder="Tháng/Ngày/Năm"  <?php if ($isUpdate==1) { $date=date_create($date);
                  $date=date_format($date, "m/d/Y" ); echo 'value= "' .$date.'"';
                            }
                                  ?>/>
                            <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </td>
          </tr>
          <tr>
            <td>Nội dung <span style="color:red;">*</span></td>
            <td><textarea row="4" cols="33" name="content"><?php if($isUpdate ==1 ){
                        echo $content;

                    }?></textarea></td>
          </tr>
          <td></td>
            <td colspan="2" > 
            <span class="inline">   <input id="nut"  type="submit" <?php if ($isUpdate==0) echo
                'name="btn_submit_add" value="Thêm" ' ; else echo 'name="btn_submit_update" value="Cập nhật" ' ;?>></span>
            <span class="inline" style="float:right; <?php if($isUpdate==0) echo ' display:none; '?>"> <input id="nut"
                type="submit" name="btn_return" value="Quay lại"></span></td>
          </tr>
        </table>
    
    </form>  </fieldset>
<!-- Tạo bảng và chèn dữ liệu cho bảng dstk-->
<?php
    //Tạo bảng và chèn dữ liệu cho bảng + phan trang-->
   
    $sql= 'select count(Event_ID) as total from tblevent';
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
        //TRUY VẤN LẤY DANH SÁCH 
        // Có limit và start rồi thì truy vấn CSDL
        $sql="SELECT * FROM `tblevent` ORDER BY `Event_Date` DESC LIMIT $start, $limit";
        $result = mysqli_query($conn, $sql);
        ?>
    <div>
      <?php 
            // PHẦN HIỂN THỊ
            //HIỂN THỊ DANH SÁCH NGÀY LỄ
          
            ?>


      <table  class="tabletable table-hover table-bordered results" style="margin-left: 10px; text-align: center;margin-right:10px; ">
        <caption>Danh sách ngày lễ</caption>
        <thead>
          <tr style="height: 45px; background-color: #fff0ea ">
            <th class="col-md-1 col-xs-1" style="text-align: center">STT</th>
            <th class="col-md-3 col-xs-3" style="text-align: center">Ngày</th>
            <th class="col-md-7 col-xs-7" style="text-align: center">Nội dung</th>            
            <th class="col-md-1 col-xs-1" style="text-align: center">Sửa</th>
            <th class="col-md-2 col-xs-2" style="text-align: center">Xóa</th>
          </tr>
        </thead>
        <tbody>
          <?php  

            $stt = ($current_page -1)*30 +1;
            if($result!=null)
            while ($data = mysqli_fetch_array($result)) {
          ?>
          <tr style="height: 40px;" <?php if($isUpdate==1 && $id==$data["Event_ID"]) echo 'id="selected";' ?>>
            <th scope="row" style="text-align: center">
              <?php echo $stt++ ?>
            </th>
            <td style="text-align: center">
              <?php 
            $date = $data["Event_Date"];
            $date = date_create($date);

            echo '<b><font color="blue">'.date_format($date,"d/m/Y").'</font></b>';       
        ?>
            </td>
            <td  style=" text-align: left; padding: 0px 10px 0px 10px; ">
              <?php  
            echo $data["Event_TitleName"];?>
            </td>
            <td><a href="event.php?<?php echo 'page='.$current_page.'&id='.$data["Event_ID"].'&acction=update'; ?>" >Sửa</a> </td>
            <td><a href="event.php?<?php echo 'id='.$data["Event_ID"].'&acction=delete'; ?>" onclick="return
                confirmAction()">Xóa</a> </td>
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