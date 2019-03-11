<?php

header('Content-Type:text/html;charset=utf-8');
// 测试接口
// http://localhost/tixian/index.php查询所有提现记录（可用作正式使用）
// http://localhost/tixian/ApiTest.php默认查询所有提现记录，可依据需要修改进行其他接口测试(需注意具体参数正确使用)，测试完建议删除
// http://localhost/tixian/TXTest.php提现1元到east账户，可修改openid为正确账户提现到其他账户，测试完成建议删除

/**
*  提现接口调用示例代码
*  查询使用get请求, 提现和删除使用post请求
*  接口地址为http://localhost/tixian/api.php
*  每个接口都需要传递参数action用以区分具体做什么且action为固定值
*/

// get请求
function httpGet($url){
    $ch = curl_init();

    $params[CURLOPT_URL] = $url;
    $params[CURLOPT_HEADER] = false;
    $params[CURLOPT_RETURNTRANSFER] = true;
    $params[CURLOPT_FOLLOWLOCATION] = true;

    curl_setopt_array($ch, $params);
    $content = curl_exec($ch);
    curl_close($curl);
    return $content;
}

// post请求
function httpPost($url, $parms) {
    $ch = curl_init();

    $params[CURLOPT_URL] = $url;    // 请求url地址
    $params[CURLOPT_HEADER] = false; // 是否返回响应头信息
    $params[CURLOPT_RETURNTRANSFER] = true; // 要求结果为字符串且输出到屏幕上
    $params[CURLOPT_FOLLOWLOCATION] = true; // 是否重定向
    $params[CURLOPT_POST] = true;
    $params[CURLOPT_POSTFIELDS] = $parms;

    curl_setopt_array($ch, $params); // 传入curl参数
    $content = curl_exec($ch); // 执行
    curl_close($ch);
    return $content;
}

$url = 'http://localhost/tixian/api.php';

// post请求
/**
* 绑定openid接口
* 使用post
* action：固定为bindopenid
* wxid：传递过来插入数据库，后期可依据wxid查询提现记录数据
* txstation：提现平台，该值需谨慎填写，对应具体的提现平台（目前使用1）
*/
$arr = [
    'action'            => 'bindopenid', // 方法名
    'wxid'              => 'wxid000003', // 微信id
    'txstation'         => 1, // 提现平台
];
$parms = urldecode(http_build_query($arr));
$res = httpPost($url, $parms);
echo $res;

/**
* 提现接口
* 使用post
* action：固定为tixian
* txmoneny：提现金额(单位为分，最小1元)
* wxid：传递过来插入数据库，后期可依据wxid查询提现记录数据
* txstation：提现平台，该值需谨慎填写，对应具体的提现平台（目前使用1）
* txid: 提现id，插入数据库
*/
// $arr = [
//     'action'            => 'tixian', // 方法名
//     'txmoney'           => 100, // 金额 单位分
//     'wxid'              => 'wxid000003', // 微信id
//     'txstation'         => 1, // 提现平台
//     'txid'              => 3,
// ];
// $parms = urldecode(http_build_query($arr));
// $res = httpPost($url, $parms);
// echo $res;


/**
* 删除某一天的数据记录接口
* 使用post
* action：固定为deletebydate
* date：需删除某一天数据的日期，以yyyy-MM-dd HH:mm:ss字符串格式传递
*/
// $arr = [
//     'action'            => 'deletebydate',
//     'date'            => '2019-03-08 22:00:11', // 日期
// ];
// $parms = urldecode(http_build_query($arr));
// $res = httpPost($url, $parms);
// echo $res;


/**
* 删除某id数据
* 使用post
* action：固定为deletebyid
* id：数据库中对应的id，正整数
*/
// $arr = [
//     'action'            => 'deletebyid',
//     'id'            => 11,
// ];
// $parms = urldecode(http_build_query($arr));
// $res = httpPost($url, $parms);
// echo $res;


/**
* 依据id数组删除数据
* 使用post
* action：固定为deleteids
* ids：数据库中对应的id数组，以'(id1, id2, id3)'字符串格式传递
*/
// $arr = [
//     'action'            => 'deleteids',
//     'ids'            => '(9, 10)',
// ];
// $parms = urldecode(http_build_query($arr));
// $res = httpPost($url, $parms);
// echo $res;



// get请求
/**
* 查询wxid提现记录
* 使用get
* action：固定为querybywxid
* wxid：需查询的wxid
*/
// $res = httpGet($url . "?action=querybywxid&wxid=wxid4");
// echo $res;

/**
* 查询所有提现记录
* 使用get
* action：固定为querylist
*/
// $res = httpGet($url . '?action=querylist');
// echo $res;
?>