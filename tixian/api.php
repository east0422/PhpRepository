<?php
include_once './Mysql.php';
include_once './Utils.php';
include_once './WxComPay.php';
include_once './RespData.php';

header('Content-Type:text/html;charset=utf-8');

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST') { // post请求
    $action = $_POST['action'];
    switch ($action) {
        case 'bindopenid': // 绑定openid
            $wxid = $_POST['wxid'];
            $txstation = $_POST['txstation'];
            $mysql = new Mysql();
            $openid = $mysql->queryOpenid($wxid, $txstation);
            if(!$openid) { 
                $utils = new Utils($txstation);
                // xxx为当前工程项目所部属对应的域名
                $baseUrl = urlencode('http://xxx/api.php?action=bindopenid&txstation='.$txstation.'&wxid='.$wxid);
                $url = $utils->_CreateOauthUrlForCode($baseUrl);
                RespData::successData($url);
                // $utils->gheader($url);   
            } else {
               RespData::error('对不起，该平台下该wxid已绑定！');
            }
            break;
        case 'tixian': // 提现
            $wxid = $_POST['wxid'];
            $txmoney = $_POST['txmoney'];
            $txstation = $_POST['txstation'];
            $txid = $_POST['txid'];
            $mysql = new Mysql();
            $openid = $mysql->queryOpenid($wxid, $txstation);
            if ($openid) {
                $wxComPay = new WxComPay();
                $wxComPay->comPay($openid, $txmoney, $wxid, $txstation, $txid); // 内部返回操作结果
            } else {
                RespData::error('对不起，您的openid还未绑定，请先绑定！');
            }
            break;
        case 'deletebydate': // 删除某一天的提现记录
            $date = $_POST['date'];
            $mysql = new Mysql();
            $mysql->deletebydate(date('Y-m-d', strtotime($date)));
            break;
        case 'deletebyid': // 依据id删除提现记录
            $id = $_POST['id'];
            $mysql = new Mysql();
            $mysql->deletebyid($id);
            break;
        case 'deleteids':
            $ids = $_POST['ids'];
            $mysql = new Mysql();
            $mysql->deletebyids($ids);
            break;
        default:
            RespData::error('post 请检查您的参数是否错误！');
            break;
    }
} else if ($method == 'GET') { // get请求
    $action = $_GET['action'];
    switch ($action) {
        case 'bindopenid': // 获取openid
            $code = $_GET['code'];
            $txstation = $_GET['txstation'];
            $wxid = $_GET['wxid'];
            if ($code) {
                $utils = new Utils($txstation);
                $openid = $utils->getOpenidFromMp($code);
                if(!$openid) { 
                    RespData::error('绑定openid失败！');
                } else {
                    $mysql = new Mysql();
                    $mysql->insertOpenid($openid, $wxid, $txstation);
                }
            }
            break;
        case 'querylist': // 查询所有提现记录
            $mysql = new Mysql();
            $res = $mysql->queryAll();
            RespData::successData($res);
            break;
        case 'querybywxid': // 查询wxid的提现记录
            $wxid = $_GET['wxid'];
            $mysql = new Mysql();
            $res = $mysql->queryByWxid($wxid);
            RespData::successData($res);
            break;
        default:
            RespData::error('get 请检查您的参数是否错误！');
            break;
    }
}

?>