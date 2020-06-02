<?php
include_once 'WXPayDBApi.php';
include_once 'RespData.php';

require_once 'lib/phpqrcode/phpqrcode.php';
require_once "lib/WxPay.Api.php";
require_once "WxPay.Config.php";
require_once "WxPay.NativePay.php";
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

header('Content-Type:text/html;charset=utf-8');

// 依据参数获取微信原生支付统一下单返回结果
function getWXNativePayCodeUrl($body, $total_fee, $out_trade_no) {
    $wxpayUnifiedOrder = new WxPayUnifiedOrder();
    $wxpayUnifiedOrder->SetBody($body);
    $wxpayUnifiedOrder->SetOut_trade_no($out_trade_no);
    $wxpayUnifiedOrder->SetTotal_fee($total_fee);
    $wxpayUnifiedOrder->SetNotify_url(dirname('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']).'/notify.php');
    $wxpayUnifiedOrder->SetTrade_type("NATIVE");
    $wxpayUnifiedOrder->SetProduct_id($wxpayUnifiedOrder->GetMch_id().$out_trade_no.date("YmdHis"));

    $nativePay = new NativePay();
    $result = $nativePay->GetPayUrl($wxpayUnifiedOrder);

    return $result;
}

// 获取微信原生支付二维码
function getWXNativePayQRCode($body, $total_fee, $out_trade_no) {
    $result = getWXNativePayCodeUrl($body, $total_fee, $out_trade_no);

    if ($result["return_code"] == 'SUCCESS') {
        if ($result["result_code"] == 'SUCCESS') {
            $codeUrl = $result["code_url"];
            if ($codeUrl) {
                if(substr($codeUrl, 0, 6) == "weixin"){
                    RespData::success(QRcode::png($codeUrl));
                } else {
                    RespData::error('对不起，原生支付统一订单生成二维码图片失败！');
                }   
            } else {
                RespData::error('对不起，原生支付统一订单返回code_url为空！');
            }
        } else {
            RespData::error($result["err_code_des"]);
        }
    } else {
        RespData::error($result["return_msg"]);
    }
}

// 微信支付查询订单
function getWxPayOrderQuery($transaction_id, $out_trade_no) {
    $wxPayOrderQuery = new WxPayOrderQuery();
    if ($transaction_id) {
        $wxPayOrderQuery->SetTransaction_id($transaction_id);
    } else {
        $wxPayOrderQuery->SetOut_trade_no($out_trade_no);
    }

    $config = new WxPayConfig();
    $result = WxPayApi::orderQuery($config, $wxPayOrderQuery);

    if ($result["return_code"] == 'SUCCESS') {
        if ($result["result_code"] == 'SUCCESS') {
            RespData::success($result);
        } else {
            RespData::error($result["err_code_des"]);
        }
    } else {
        RespData::error($result["return_msg"]);
    }
}

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST') { // post请求
    $action = $_POST['action'];
    switch ($action) {
        case 'wxnativepayurl': // 微信原生支付统一下单返回url
            $body = $_POST['desc'];
            $total_fee = $_POST['money'];
            $out_trade_no = $_POST['orderno'];

            $result = getWXNativePayCodeUrl($body, $total_fee, $out_trade_no);
            if ($result["return_code"] == 'SUCCESS') {
                if ($result["result_code"] == 'SUCCESS') {
                    RespData::success($result);
                } else {
                    RespData::error($result["err_code_des"]);
                }
            } else {
                RespData::error($result["return_msg"]);
            }

            break;
        case 'wxnativepayqrcode': // 微信原生支付统一下单返回二维码图片
            $body = $_POST['desc'];
            $total_fee = $_POST['money'];
            $out_trade_no = $_POST['orderno'];

            getWXNativePayQRCode($body, $total_fee, $out_trade_no);
            
            break;
        case 'wxpayorderquery':
            $transaction_id = $_POST['transactionid'];
            $out_trade_no = $_POST['orderno'];

            getWxPayOrderQuery($transaction_id, $out_trade_no);
            break;
        default:
            RespData::error('post 请检查您的action参数是否错误！');
            break;
    }
} else if ($method == 'GET') { // get请求
    $action = $_GET['action'];
    switch ($action) {
        case 'wxnativepayurl': // 微信原生支付统一下单返回url
            $body = $_GET['desc'];
            $total_fee = $_GET['money'];
            $out_trade_no = $_GET['orderno'];

            $result = getWXNativePayCodeUrl($body, $total_fee, $out_trade_no);
            if ($result["return_code"] == 'SUCCESS') {
                if ($result["result_code"] == 'SUCCESS') {
                    RespData::success($result);
                } else {
                    RespData::error($result["err_code_des"]);
                }
            } else {
                RespData::error($result["return_msg"]);
            }

            break;
        case 'wxnativepayqrcode': // 微信原生支付统一下单返回二维码图片
            $body = $_GET['desc'];
            $total_fee = $_GET['money'];
            $out_trade_no = $_GET['orderno'];

            getWXNativePayQRCode($body, $total_fee, $out_trade_no);
            
            break;
        case 'wxpayorderquery':
            $transaction_id = $_GET['transactionid'];
            $out_trade_no = $_GET['orderno'];

            getWxPayOrderQuery($transaction_id, $out_trade_no);
            break;

        // wxpay数据库表查询
        case 'querywxpay':
            $wxPayDBApi = new WXPayDBApi();
            $res = $wxPayDBApi->queryAll();
            RespData::success($res);
            break;
        case 'querywxpaybyorderno':
            $out_trade_no = $_GET['orderno'];
            $wxPayDBApi = new WXPayDBApi();
            $res = $wxPayDBApi->queryByOutTradeNo($out_trade_no);
            RespData::success($res);
            break;
        case 'querywxpaybytransactionid':
            $transaction_id = $_GET['transactionid'];
            $wxPayDBApi = new WXPayDBApi();
            $res = $wxPayDBApi->queryByTransactionId($transaction_id);
            RespData::success($res);
            break;
        default:
            RespData::error('get 请检查您的action参数是否错误！');
            break;
    }
}

?>