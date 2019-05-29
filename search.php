<?php session_start(); ?> 
  <!DOCTYPE html>
<html>

<head>
  <title>Tìm kiếm lịch</title>
  <link rel="icon" type="image/png" href="images/qnict.ico"/>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" href="css/3.4.0.bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/3.3.6.bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/4.17.37.bootstrap-datetimepicker.min.css">
  <script type="text/javascript" src="js/1.12.4.jquery.min.js"></script>
  <script type="text/javascript" src="js/2.14.1.moment.min.js"></script>
  <script type="text/javascript" src="js/3.3.6.bootstrap.min.js"></script>
  <script type="text/javascript" src="js/4.17.37.bootstrap-datetimepicker.min.js"></script>

  <script type="text/javascript">
            $(function () {
                $('#datetimepicker3').datetimepicker({
                    format: 'MM/DD/YYYY'
                });
            });
        </script>
          <script type="text/javascript">
            $(function () {
                $('#datetimepicker4').datetimepicker({
                    format: 'MM/DD/YYYY'
                });
            });
        </script>


  
   <link rel="stylesheet" href="css/stylesmenu.css">
 
   <script src="js/script.js"></script>
  <style>
    /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
      .row.content {
        min-height: 450px;
          
        }
       /* Remove the navbar's default margin-bottom and rounded borders */
       .navbar {
        min-height: 450px;
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


        .fstElement { font-size: 1.2em; }
.fstToggleBtn { min-width: 16.5em; }

.fstMultipleMode { display: block; }
.fstMultipleMode .fstControls { width: 100%; }

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
require_once("include/connection.php");  
$sqlSearch="";
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
   <li ><a href='index.php'>Lịch công tác</a></li>
   <li ><a href='department.php' style="cursor: default;">Các phòng ban</a>
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
   <li class='active'><a href='search.php'>Tìm kiếm</a></li>
   <li><a href='manager/login.php'>Đăng nhập quản trị</a></li>
   <li style=" <?php if ($checklogin ==0) echo 'display:none;'?>">
            <a href ="manager/logout.php">Đăng xuất</a></li>
   
</ul>
</div>
        </div>


      <div class="container-fluid" style="background-color: #F0F8FF; " >
     

      <div class="row" style="background-color: antiquewhite; padding-top:5px; color:red;  " id="ngayle">
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
      <?php



//KHI NGUOI DUNG NHAP VAO NUT tìm kiếm
if (isset($_POST["btn_submit_search"] ) && $checklogin==1) {
    // Nhận dữ liệu và gán vào các biến đồng thời xử lý chuỗi
    
  
    $datestart = @($_POST['datestart']);
    $dateend = @($_POST['dateend']);
    $content = @$_POST['content'];

    //Làm sạch
    $datestart = strip_tags($datestart);
    $datestart = addslashes($datestart);
    $content = strip_tags($content);
    $content = addslashes($content);
    $dateend = strip_tags($dateend);
    $dateend = addslashes($dateend);
    $checkDate=1;
    try {
        
            $datestart=date_create($datestart);

            $datestart= date_format($datestart, "Y-m-d");
            $dateend=date_create($dateend);

            $dateend= date_format($dateend, "Y-m-d");
        
    } catch (Exception $e) {
  
  //lỗi ngày tháng
        $checkDate= 0;
        // echo 'lỗi thời gian';
    }
    if ($checkDate == 0) {
        //$notice= 6;
        echo 'Lỗi ngày tháng.';
    }
    elseif($datestart==""){
      $sqlSearch = " WHERE `Schedule_Date` >= '2000-01-01' AND `Schedule_Date` <= '$dateend' AND `Schedule_Content` like '%$content%' ";
    }elseif($dateend==""){
      $sqlSearch = " WHERE `Schedule_Date` >= '$datestart' AND `Schedule_Date` <= '".date(Y-m-d)."' AND `Schedule_Content` like '%$content%' ";
    }
    elseif($dateend==""&& $datestart==""){
      $sqlSearch = " WHERE `Schedule_Date` >= '2000-01-01' AND `Schedule_Date` <= '".date(Y-m-d)."' AND `Schedule_Content` like '%$content%' ";
    }
    // Ngược lại
    else {
        // Lệnh SQL search
        
            $sqlSearch = " WHERE `Schedule_Date` >= '$datestart' AND `Schedule_Date` <= '$dateend' AND `Schedule_Content` like '%$content%' ";
       
            
    }
}
?>
  <div>
    <form method="POST" action="search.php"style="padding-left:10px;"  > 
      <fieldset>
        <legend>Tìm kiếm</legend>
        <?php if ($checklogin ==0) echo '<form style=" width: 500px;height:250px;">
              <table>
                  <tr >
                      <td style="padding-left: 10px; font-size: 15px; width:70%; "> <br>  Bạn cần <a href="login.php" style=" text-decoration: none;"><b>đăng nhập</b> </a> để xem được lịch công tác.
                          <p>
                          Nếu chưa có tài khoản, hãy liên hệ admin tại phòng Hành chính tổng hợp để được hỗ trợ.</p>
                          <p>Email: abc.xt123@gmail.com<br>SĐT: 012 345 678</p>
                      </td>
                      <td><img style="display: block; margin-left: auto; margin-right: auto; width: 150px;height:150px;margin-left:-10px  "src="images/lock.png "></td>
                  </tr>
              </table>
          </form>
          <hr>
					
        ';?>
        <table>
          <tr>
            <td>Thời gian bắt đầu</td>
            <td>
              <div class='input-group date' id='datetimepicker3'>
                <input type='text' class="form-control" name="datestart" placeholder="Tháng/Ngày/Năm "
                <?php 
                // if ($isUpdate==1) { $date=date_create($date);
                //     $date=date_format($date, "m/d/Y" ); echo 'value= "' .$date.' '.$time.'"';
                //               }
                                  ?>/>
                            <span class="
                  input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </td>
          </tr>
          <tr>
            <td>Thời gian kết thúc</td>
            <td>
              <div class='input-group date' id='datetimepicker4'>
                <input type='text' class="form-control" name="dateend" placeholder="Tháng/Ngày/Năm "
                <?php 
                // if ($isUpdate==1) { $date=date_create($date);
                //     $date=date_format($date, "m/d/Y" ); echo 'value= "' .$date.' '.$time.'"';
                //               }
                                  ?>/>
                            <span class="
                  input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>
            </td>
          </tr>
          <tr>
            <td > Nội dung  </td>
            <td>&nbsp;<textarea row="4" cols="33" name="content"><?php 
            // if($isUpdate ==1 ){
            //     echo $content;

            // }
            ?></textarea></td>
          </tr>
         
          <tr >
             
             <td></td>
              <td  colspan ="2";>
              <span class="inline"> &nbsp;<input id="nut"  type="submit" name="btn_submit_search" value="Tìm kiếm"   ></span>
             
              </td>
          </tr>
        </table>
      </fieldset>
    </form>
    <?php
    //Tạo bảng và chèn dữ liệu cho bảng + phan trang-->
    if ($sqlSearch=="")
    {
        //die;
    }
    else
    {
    
        $sql= "select count(Schedule_ID) as total from tblschedule ".$sqlSearch;
    
  
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
    }
        if ($sqlSearch=="")
       {
          // die;
       }        else{
        $sql= "SELECT * FROM `tblschedule` INNER JOIN  `tbldepartment` on `tblschedule`.`Schedule_DepartmentID`=`tbldepartment`.`Department_ID` ".$sqlSearch."ORDER BY `Schedule_Date` DESC LIMIT $start, $limit";
        $result = mysqli_query($conn, $sql);
        ?>
    <div>
      <?php 
            // PHẦN HIỂN THỊ
            //HIỂN THỊ DANH SÁCH LỊCH
       
            ?>


      <table class="tabletable table-hover table-bordered results" style="margin-left: 10px; text-align: center;margin-right:10px; ">
        <caption> Danh sách lịch làm việc</caption>
        <thead>
          <tr  style="height: 45px; background-color: #fff0ea">
            <th class="col-md-1 col-xs-1" style="text-align: center">STT</th>
            <th class="col-md-2 col-xs-2" style="text-align: center">Ngày</th>
            <th class="col-md-5 col-xs-5" style="text-align: center">Nội dung</th>
            <th class="col-md-4 col-xs-4" style="text-align: center">Phòng, ban</th>
            
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
            </td >
            <td  style=" text-align: justify; padding: 5px 10px 5px 10px; ">
                <!-- kkkkkkkk -->
              <?php  $date = date_create($data["Schedule_Time"]);
            echo '<b><font color="red">'.date_format($date,"H:i").'</font></b> '; 
            echo $data["Schedule_Content"];?>
            </td>
            <td style="  text-align: center; padding: 0px 10px 0px 10px;">
                        <?php echo $data["Department_Name"]; ?>
                    </td>
            
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
            } }
          ?>
       
    </div>

    <footer class="container-fluid text-center">
      <p>Bản quyền thuộc về Trung tâm Công nghệ thông tin và Truyền thông Quảng Ninh
      </p>
      <p>All rights reserved.</p>
    </footer>
  </div></div></div>
        </div>
</body>

</html>