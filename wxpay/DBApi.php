<?php
require_once 'RespData.php';

header('content-type:text/html;charset=utf-8');

date_default_timezone_set('Etc/GMT-8');

/* 
 * 数据库操作类
 */
class DBApi {
    private $hostname = 'localhost';
    private $username = 'root';
    private $password = 'root';
    private $port = '3306';
    private $dbname = 'payrecord';

    public $conn; // 连接句柄
 
    // 连接数据库构造函数
    public function __construct() {
        $this->conn = mysqli_connect($this->hostname, $this->username, $this->password, $this->dbname, $this->port);
        if (!$this->conn){
            RespData::error('服务器连接数据库有点小问题，请联系我们！');
        }
    }
    // 查询所有记录
    public function listAll($tbname) {
        $result = mysqli_query($this->conn, "select * from $tbname");
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
    // 查询tbname表中id对应的记录
    public function listById($tbname, $id) {
        $result = mysqli_query($this->conn, "select * from $tbname where id = '$id'");
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
    // 查询tbname表中字段fieldname值为fieldvalue对应的记录
    public function listByField($tbname, $fieldname, $fieldvalue) {
        $result = mysqli_query($this->conn, "select * from $tbname where $fieldname = '$fieldvalue'");
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
    // 删除某一条记录数据
    public function delbyid($tbname, $id) {
        mysqli_query($this->conn, "delete from $tbname where id = '$id'");
        $affectedRows = mysqli_affected_rows($this->conn);
        if($affectedRows > 0) {
            RespData::success('删除' . $id .'成功！');
        } else {
            RespData::error('删除' . $id . '失败！');
        }
    }
    // 删除某些记录数据
    public function delbyids($tbname, $uids) {
        mysqli_query($this->conn, "delete from $tbname where id in ('$uids')");
        $affectedRows = mysqli_affected_rows($this->conn);
        if($affectedRows > 0) {
            RespData::success('删除成功！');
        } else {
            RespData::error('删除失败！'.$ids);
        }
    }

    /******** sql操作 *******/
    // 依据sql语句插入数据表记录
    public function insertBySql($sqlStr) {
        mysqli_query($this->conn, $sqlStr);
        $affectedRows = mysqli_affected_rows($this->conn);
        if ($affectedRows > 0) {
            RespData::success('插入数据成功！');
        } else {
            RespData::error('插入数据失败！');
        }
    }
    // 依据sql语句删除数据表记录
    public function deleteBySql($sqlStr) {
        mysqli_query($this->conn, $sqlStr);
        $affectedRows = mysqli_affected_rows($this->conn);
        if ($affectedRows > 0) {
            RespData::success('插入数据成功！');
        } else {
            RespData::error('插入数据失败！');
        }
    }
    // 依据sql语句更改数据表记录
    public function updateBySql($sqlStr) {
        mysqli_query($this->conn, $sqlStr);
        $affectedRows = mysqli_affected_rows($this->conn);
        if ($affectedRows > 0) {
            RespData::success('更改数据成功！');
        } else {
            RespData::error('更改数据失败！');
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

    // 断开连接析构函数
    public function __destruct()
    {
        mysqli_close($this->conn);
    }
}
