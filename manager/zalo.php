<?php
session_start();
ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Zalo</title>
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
 require_once("../include/ZaloFuntions.php");
 $notice=-1;
?>

<?php 
//$fp = @fopen('../olaz/info.txt', "r");

// Kiểm tra file mở thành công không
//if (!$fp ) {
  //  echo 'Vui lòng kiểm tra lại file info.';
//}
//else
//{
    // Lặp qua từng dòng để đọc
 //   while(!feof($fp))
  //  {
    //    $json=fgets($fp);
  //  }
//}
  $json=file_get_contents('../olaz/info.json');
$json = json_decode($json, true);
$app_id = $json["zalo"]["app_id"];
$direct_url= $json["zalo"]["direct_url"];
$access_token= $json["zalo"]["access_token"];
$isLive= $json["zalo"]["isLive"];

?>
<?php
//xử lý sự kiện khi người dùng tìm kiếm số đt
if(isset($_POST['PhoneNumber'])){
    
    $phone =$_POST['PhoneNumber'];
    if($phone!=""){
    $result=getUserInfoWithPhone($phone,$access_token);
    $result = json_decode($result,true);
    $error = @$result["error"];
    $message = @$result["message"];
    $notice =1;
    if($error == 0 || $error ==1 ){
        $uid = $result["data"]["user_id"];
        $avatar = $result["data"]["avatars"]["240"];
        $name = $result["data"]["display_name"];
        $user_gender = $result["data"]["user_gender"];
    }
   
    
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
        <fieldset>
            <legend>Tìm UID Zalo</legend>
            <form action="zalo.php" method="post" style="padding-left: 25px; padding-top: 10px;">
                <table>
                <tr>
                    <a href="https://oauth.zaloapp.com/v3/oa/permission?app_id=<?php echo $app_id;?>&redirect_uri=<?php echo $direct_url;?>" target="_blank"> Nhấn vào đây để lấy mã truy cập OA Zalo </a>
                        <td></td>

                        
                        <td id="notice">
                                 <?php 
                            if($notice == 1 && $error==0 && $error ==1) echo '<div class="alert-box success">'.$message.'</div>';
                           
                            elseif($notice ==1) echo'<div class="alert-box error">'.$message.'</div>';
                           
                        ?>

                        </td>
                    </tr>
                    <tr>
                        <td> Nhập số điện thoại <span style="color:red;">*</span> &nbsp; </td>
                        <td><input name="PhoneNumber" type="text" >
                            
                                   </td>
    
                    </tr>
                    <tr>
                    <td></td>
                    <td colspan="2" align="center">
                        
                        <span class="inline" style="float:left; " > <input id="nut" type="submit" name="btn_submit" value="Thực hiện"></span>
                        </td>
                    </tr>
               </table>
</form>
        </fieldset>
        <?php 
     if(isset($error)&&($error == 0 || $error ==1) ){ ?>
          <img src="<?php echo $avatar; ?>" style="width:25%">
  <h1>Tên Zalo: <?php echo $name; ?></h1>

  <p >UID: <?php echo $uid; ?></p>
  <p>Giới tính: <?php if($user_gender == 1) echo "Nam"; else echo "Nữ"; ?></p>

    <?php }


?>
    </div>

    <footer class="container-fluid text-center">
        <p>Bản quyền thuộc về Trung tâm Công nghệ thông tin và Truyền thông Quảng Ninh
        </p>
        <p>All rights reserved.</p>
    </footer>
</body>

</html>
<?php ob_end_flush(); ?>