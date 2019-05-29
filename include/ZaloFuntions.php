<?php

// var_dump(  $result['data']['total']) ;  
// var_dump(  $result['data']['followers'][3]["user_id"]) ;      
// sendMessageFromOA("5499104223938322963",$accsesstoken,"Cuộc đời này có mấy lần 10 năm?");
function getUserInfoWithPhone($phone, $accsesstoken){

$url='https://openapi.zalo.me/v2.0/oa/getprofile?access_token='.$accsesstoken.'&data={"user_id":"'.$phone.'"}';
$result= file_get_contents($url);
return $result;

}


function sendMessageFromOA($uid, $accsesstoken, $msg ){
    $msg= $msg."";
    $param = array('user_id' =>"$uid");
    $message = array('text' => "$msg" );
    $param = array(
        'recipient' => $param,
        'message' =>  $message
    );
    $param = json_encode($param);
    $url = 'https://openapi.zalo.me/v2.0/oa/message?access_token='.$accsesstoken;
$curl = curl_init($url);

curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, $param);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($param))
);

$result = curl_exec($curl);
return $result;
curl_close($curl);
  
}
?>