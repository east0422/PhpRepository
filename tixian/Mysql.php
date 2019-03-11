<?php
include_once './RespData.php';

header('content-type:text/html;charset=utf-8');

date_default_timezone_set('Etc/GMT-8');

/* 
 * 数据库操作类
 */
class Mysql{
    private $hostname = 'localhost';
    private $username = 'root';
    private $password = 'root';
    private $port = '3306';
    private $dbname = 'tixian';
    private $tbname = 'wxcompay';
    private $conn; // 连接句柄
 
    // 连接数据库构造函数
    public function __construct()
    {
        $this->conn = mysqli_connect($this->hostname, $this->username, $this->password, $this->dbname, $this->port);
        if (!$this->conn){
            RespData::error('服务器连接数据库有点小问题，请联系我们！');
        }
    }
    // 绑定openid将openid, wxid插入user表
    public function insertOpenid($_openid, $_wxid, $_txstation){
        $stmt = $this->conn->prepare("insert into user(openid, wxid, txstation, createtime) values (?, ?, ?, ?)");
        $stmt->bind_param('ssis', $openid, $wxid, $txstation, $createtime);
        $openid = $_openid;
        $wxid = $_wxid;
        $txstation = $_txstation;
        $createtime = date('Y-m-d H:i:s');
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            RespData::success('恭喜您，绑定openid成功！');
        } else {
            RespData::error('对不起，绑定openid失败！');
        }
    }
    // 查询wxid对应的openid
    public function queryOpenid($_wxid, $_txstation){
        $result = mysqli_query($this->conn, "select openid from user where wxid = '$_wxid' and txstation = $_txstation");
        if($result && ($row = mysqli_fetch_row($result))) {
            mysqli_free_result($result);
            return $row[0];
        } else {
            return null;
        }
    }
    // 依据字段插入数据表记录
    public function insert($_wxid, $_txmoney, $_txstation, $_txid, $_txorder, $_txtime){
        $stmt = $this->conn->prepare("insert into $this->tbname(wxid, txmoney, txstation, txid, txorder, txtime) values (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sdiiss', $wxid, $txmoney, $txstation, $txid, $txorder, $txtime);
        $wxid = $_wxid;
        $txmoney = $_txmoney;
        $txstation = $_txstation;
        $txid = $_txid;
        $txorder = $_txorder;
        $txtime = $_txtime;
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            RespData::successData(array(
                'txstation' => $_txstation,
                'txid' => $_txid,
            ));
        } else {
            RespData::error('对不起，插入数据失败！');
        }
    }
    // 依据sql语句插入数据表记录
    public function insertBySql($sqlStr){
        if (!mysqli_query($this->conn, $sqlStr)){
            RespData::error('插入数据失败！');
        } else {
            RespData::success('插入数据成功！');
        }
    }
    // 查询所有记录
    public function queryAll(){
        $result = mysqli_query($this->conn, "select id, wxid, txmoney, txstation, txid, txorder, txtime from $this->tbname");
        if($result) {
            // 返回一个关联数组
            $row = [];
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $rows[] = $row;
            }
            mysqli_free_result($result);
            return $rows;
        } else {
            return null;
        }
    }
    // 查询wxid对应的记录
    public function queryByWxid($_wxid){
        $result = mysqli_query($this->conn, "select id, wxid, txmoney, txstation, txid, txorder, txtime from $this->tbname where wxid = '$_wxid'");
        if($result) {
            // 返回一个关联数组
            $row = [];
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $rows[] = $row;
            }
            mysqli_free_result($result);
            return $rows;
        } else {
            return null;
        }
    }
    // 查询平台代号对应的记录
    public function queryByTxstation($_txstation){
        $result = mysqli_query($this->conn, "select id, wxid, txmoney, txstation, txid, txorder, txtime from $this->tbname where txstation = $_txstation");
        if($result) {
            // 返回一个关联数组
            $row = [];
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $rows[] = $row;
            }
            mysqli_free_result($result);
            return $rows;
        } else {
            return null;
        }
    }
    // 查询wxid在某平台代号对应的记录
    public function queryByWxidAndTxstation($_wxid, $_txstation){
        $result = mysqli_query($this->conn, "select id, wxid, txmoney, txstation, txid, txorder, txtime from $this->tbname where wxid = '$_wxid' and txstation = $_txstation");
        if($result) {
            // 返回一个关联数组
            $row = [];
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $rows[] = $row;
            }
            mysqli_free_result($result);
            return $rows;
        } else {
            return null;
        }
    }
    // 依据sql语句查询数据表记录
    public function queryBySql($sqlStr){
        $result = mysqli_query($this->conn, $sqlStr);
        if($result) {
            // 返回一个关联数组
            $row = [];
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $rows[] = $row;
            }
            mysqli_free_result($result);
            return $rows;
        } else {
            return null;
        }
    }
    // 删除某一天的记录数据
    public function deletebydate($date){
        $result = mysqli_query($this->conn, "delete from $this->tbname where date(`txtime`) = '$date'");
        if($result) {
            RespData::success('删除' . $date . '记录成功！');
        } else {
            RespData::error('删除' . $date . '失败！');
        }
    }
    // 删除某一条记录数据
    public function deletebyid($id){
        $result = mysqli_query($this->conn, "delete from $this->tbname where id = $id");
        if($result) {
            RespData::success('删除' . $id .'成功！');
        } else {
            RespData::error('删除' . $id . '失败！');
        }
    }
    // 删除某一条记录数据
    public function deletebyids($ids){
        $result = mysqli_query($this->conn, "delete from $this->tbname where id in $ids");
        if($result) {
            RespData::success('删除成功！');
        } else {
            RespData::error('删除失败！');
        }
    }
    // 依据sql语句删除数据表记录
    public function deleteBySql($sqlStr){
        if (!mysqli_query($this->conn, $sqlStr)){
            RespData.error('删除数据失败！');
        }
    }
    // 断开连接析构函数
    public function __destruct()
    {
        mysqli_close($this->conn);
    }
}
