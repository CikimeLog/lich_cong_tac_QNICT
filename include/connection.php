<?php
$server_username = "qnict_lct"; 
$server_password = "7w*7kTi1";
$server_host = "localhost:3306"; 
$database = 'lichcongtacstt_lct'; 
 
// Tạo kết nối đến database 
$conn = mysqli_connect($server_host,$server_username,$server_password,$database) or die("không thể kết nối tới database");
// Thiết lập kết nối khi truy vấn là dạng UTF8 trong trường hợp dữ liệu là tiếng việt có dâu
mysqli_query($conn,"SET NAMES 'UTF8'");
