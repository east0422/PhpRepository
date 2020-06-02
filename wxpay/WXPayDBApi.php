<?php

require_once 'DBApi.php';
require_once 'RespData.php';

header('content-type:text/html;charset=utf-8');

date_default_timezone_set('Etc/GMT-8');

/* 
 * 微信原生支付wxpay数据库表
 */
class WXPayDBApi extends DBApi {

    private $tbname = 'wxpay'; // 用户表名
 
    // 依据字段插入数据表记录
    public function add($out_trade_no, $total_fee, $trade_type, $openid, $transaction_id, $trade_time) {
        $stmt = $this->conn->prepare("insert into $this->tbname(out_trade_no, total_fee, trade_type, openid, transaction_id, trade_time) values (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sissss', $out_trade_no, $total_fee, $trade_type, $openid, $transaction_id, $trade_time);
        $stmt->execute();
        $affectedRows = mysqli_affected_rows($this->conn);
        $stmt->close();

        return ($affectedRows > 0);
        // 微信原生扫码支付成功后插入支付记录到数据库
        // if ($affectedRows > 0) {
        //     RespData::success('微信支付记录加入数据库成功！');
        // } else {
        //     RespData::error('对不起，微信支付记录加入数据库失败！');
        // }
    }
    // 查询所有用户记录
    public function queryAll() {
        return parent::listAll($this->tbname);
    }
    // 查询id对应的记录
    public function queryById($id) {
        return parent::listById($this->tbname, $id);
    }
    // 查询out_trade_no对应的记录
    public function queryByOutTradeNo($out_trade_no) {
        return parent::listByField($this->tbname, 'out_trade_no', $out_trade_no);
    }
    // 查询id对应的记录
    public function queryByTransactionId($transaction_id) {
        return parent::listByField($this->tbname, 'transaction_id', $transaction_id);
    }
}
