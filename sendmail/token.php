


<?php 
if(isset($_GET["access_token"])){
//    $fp = @fopen('../olaz/info.txt', "r");
  
//// Kiểm tra file mở thành công không
//if (!$fp) {
 //   echo 'Vui lòng kiểm tra lại file info.';
//}
//else
//{
//    // Lặp qua từng dòng để đọc
//    while(!feof($fp))
//    {
//        $json=fgets($fp);
 //   }
//}
	$json=file_get_contents('../olaz/info.json');
$json = json_decode($json, true);
$json["zalo"]["access_token"] =$_GET["access_token"];
$json["zalo"]["isLive"] =1;

$json = json_encode($json);

//$fpt = @fopen('../olaz/info.txt', "w+");
  
// Kiểm tra file mở thành công không
//if (!$fpt) {
 //   echo 'Vui lòng kiểm tra lại file info.';
//}
//else
//{
 
  //  fwrite($fpt, $json);
 //   echo 'aa';
//}
if(file_put_contents('../olaz/info.json',$json)==false){echo "chen token vao file info that bai";}
	else echo "Da tu dong chen token vao file info";
}



?>