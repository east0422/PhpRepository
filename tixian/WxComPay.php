<?php
include_once './Conf.php';
include_once './Mysql.php';
include_once './RespData.php';

header('Content-type: text/html;charset=utf-8');

/* 
 * 微信企业付款到零钱
 */
class WxComPay
{
    private $openid; // 提现用户openid
    private $txmoney; // 提现金额(单位为分)
    private $wxid; // 提现用户微信
    private $txstation; // 提现平台代号
    private $txid;  // 平台提现id

    public function comPay($_openid, $_txmoney, $_wxid, $_txstation, $_txid){
        $this->openid = $_openid;
        $this->txmoney = $_txmoney;
        $this->wxid = $_wxid;
        $this->txstation = $_txstation;
        $this->txid = $_txid;
        //将数据发送到接口地址
        $this->tixian();
    }
    public function tixian(){
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $conf = new Conf($this->txstation);
        $params = [
            'mch_appid'         => $conf->MCHAPPID, // 商户账号appid,
            'mchid'             => $conf->MCHID, // 商户号,
            'nonce_str'         => $conf->getNonceStr(), // 随机字符串
            'partner_trade_no'  => $conf->MCHID.date('YmdHis').rand(1000, 9999), // 商户订单号
            'openid'            => $this->openid, // 用户openid
            'check_name'        => 'NO_CHECK',  // 校验用户姓名选项
            'amount'            => $this->txmoney, // 金额 单位分
            'desc'              => '提现', // 付款描述
            'spbill_create_ip'  => $_SERVER['SERVER_ADDR'], //调用接口机器的ip地址
        ];
        $arr = $conf->makeSign($params);
        $xml = $conf->arrToXml($arr);
        $returnData = $conf->postXmlCurl($url, $xml);
        $res = $conf->xmlToArr($returnData);
        if ($res['return_code'] == 'SUCCESS') {
            if ($res['result_code'] == 'SUCCESS') {
                $mysql = new Mysql();
                $mysql->insert($this->wxid, $this->txmoney, $this->txstation, $this->txid, $res['payment_no'], $res['payment_time']);
            } else {
                RespData::error($res['err_code_des']);
            }
        } else {
            RespData::error($res['err_code_des']);
        }
    }
}
