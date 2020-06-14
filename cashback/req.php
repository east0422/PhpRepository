<?php 
header("content:application/json;chartset=uft-8");
//header("content-Type:text/html; Charset=UTF-8");

header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Headers: Content-Type");

$arr = array();
 foreach ($_REQUEST as $key => $value)
 {
$arr[$key]=$value;
};
$str= $arr['msg'];

$encode=urlencode($str); 


$url='apiurl';


$data = "msg=".$encode.'&user='.$arr['user'].'&inviter='.$arr['inviter'];
$ch = curl_init();
//设置curl参数
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setOPt($ch,CURLOPT_CONNECTTIMEOUT,10);
curl_setOPt($ch,CURLOPT_POST,true);
curl_setOPt($ch,CURLOPT_POSTFIELDS,$data);

$Respone=curl_exec($ch);
curl_close($ch);

$Respone=urldecode($Respone);
//$Respone=str_replace('\n', "<br/>", $Respone);
print($Respone);