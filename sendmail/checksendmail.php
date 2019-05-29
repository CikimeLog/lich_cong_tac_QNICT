<?php 
require_once("../include/connection.php");
require_once("PHPMailer/class.smtp.php");
require_once("PHPMailer/class.phpmailer.php"); 
require_once("sendmail.php");
require_once("../include/ZaloFuntions.php"); 
date_default_timezone_set('Asia/Ho_Chi_Minh');

//$fp = @fopen('../olaz/info.txt', "r");
  //
// Kiểm tra file mở thành công không
//if (!$fp) {
//    echo 'Vui lòng kiểm tra lại file info.';
//}
//else
//{
    // Lặp qua từng dòng để đọc
 //   while(!feof($fp))
   // {
     //   $json=fgets($fp);
    //}
//}
	$json=file_get_contents('../olaz/info.json');
$json = json_decode($json, true);
$email = $json["gmail"]["email"];
$pass= $json["gmail"]["pass"];
$access_token= $json["zalo"]["access_token"];
$isLive= $json["zalo"]["isLive"];

$date=date('Y-m-d');

$start   = date('H:i:s', mktime(date('H'), date('i')-10, 0, date('m'), date('d'),date('Y')));
$end= date('H:i:s', mktime(date('H'), date('i')+30, 0, date('m'), date('d'),date('Y')));
if(date("H")==23 && date("m")>=30) $end= date('H:i:s', mktime(date('H'), date('i')+ (59- date('i')), 0, date('m'), date('d'),date('Y')));
if(date("H")==0 && date("m")<=10) $start= date('H:i:s', mktime(date('H'), date('i')-date('i'), 0, date('m'), date('d'),date('Y')));
   // echo $start."  ".$end;
    $sql= "SELECT * FROM (`tblschedule` INNER JOIN `tblmember_schedule` ON tblschedule.Schedule_ID=tblmember_schedule.Schedule_ID)";
    $sql= $sql."INNER JOIN `tblmember` ON tblmember.Member_ID=tblmember_schedule.Member_ID WHERE `Schedule_Date` ='$date' ";
    $sql= $sql."AND `Schedule_Time`>='$start' AND `Schedule_Time` <= '$end' AND `tblschedule`.`Schedule_ID` IN ";
    $sql= $sql."(SELECT `Schedule_ID` FROM `tblmember_schedule` WHERE `Notified` ='0')";
   $total=0;
   $count=0;

    $result = mysqli_query($conn, $sql);

    while ($data = mysqli_fetch_array($result)) {
        $count ++;
     
        $date = $data["Schedule_Date"];
        $date = date_create($date);
        $date= date_format($date,"d/m/Y");
        $time = $data["Schedule_Time"];
        $time = date_create($time);
        $time= date_format($time,"h:i");
        if($data['Member_Choose']==1){
        $title= 'Bạn có lịch công tác vào '.$time.', ngày '.$date.'.';
        $content = '<p style="text-align:justify">'.$data['Schedule_Content'].'</p> <p style=" text-align:justify"><em>Vui l&ograve;ng truy cập v&agrave;o:&nbsp;http://lctstt.qnict.vn để biết th&ecirc;m chi tiết.</em></p>';
        $nTo=$data['Member_Name'];
        $mTo=$data['Member_Email'];
       


        $kq= sendMail($email,$pass,$title,$content,$nTo,$mTo,$diachicc='');
        if($kq==1){
        $memberid= $data['Member_ID'];
      
         $total +=1;
        $scheduleid= $data['Schedule_ID'];
      
        $sql = "UPDATE `tblmember_schedule` SET `Notified`='1' WHERE `Member_ID`='$memberid' AND `Schedule_ID`= '$scheduleid'";
        // Thực hiện truy vấn
       $query= mysqli_query($conn, $sql);
        }

    }else if($data['Member_Choose']==2){
        $title= 'Bạn có lịch công tác vào '.$time.', ngày '.$date.'. với nội dung là: ';
        $content =html_entity_decode(rtrim($data['Schedule_Content'])).'
                Vui lòng truy cập vào: http://lctstt.qnict.vn để xem thêm chi tiết.';
        $content = $title.$content;
        $content= strip_tags($content);
       
        $kq= sendMessageFromOA($data["Member_ZaloID"],$access_token,$content);
        $kq = json_decode($kq, true);
        $error = $kq["error"];
		
        if($error != null && $error ==0){
        $memberid= $data['Member_ID'];
      
         $total +=1;
        $scheduleid= $data['Schedule_ID'];
      
        $sql = "UPDATE `tblmember_schedule` SET `Notified`='1' WHERE `Member_ID`='$memberid' AND `Schedule_ID`= '$scheduleid'";
        // Thực hiện truy vấn
       $query= mysqli_query($conn, $sql);
					$json["zalo"]["isLive"] = 1;
			$json = json_encode($json);
			file_put_contents('../olaz/info.json',$json);
        }else if($error==-216){
		$json["zalo"]["isLive"] = 0;
			$json = json_encode($json);
			file_put_contents('../olaz/info.json',$json);
		}

    }
    }
   echo $total."/".$count;

?>
