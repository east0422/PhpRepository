<?php
include_once './WxComPay.php';

header('Content-Type:text/html;charset=utf-8');

try{
    $openid = 'o1OYX0pELR9VPNuNxUAuTOdytPS8';
    $wxComPay = new WxComPay();
    $wxComPay->comPay($openid, 100, 'wxid000001', 1, 1);
} catch(Exception $err) {
    var_dump($err);
}
?>