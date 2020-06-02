<?php
include_once './DBApi.php';
include_once './RespData.php';

header('content-type:text/html;charset=utf-8');

date_default_timezone_set('Etc/GMT-8');

/* 
 * 管理员操作类(登录、注册、更改用户名密码)
 */
class AdminUserApi extends DBApi {

    private $tbname = 'admin_user'; // 用户表名
 
    // 依据字段插入数据表记录
    public function add($username, $password) {
        $result = mysqli_query($this->conn, "insert into $this->tbname(username, password) values ('$username', '$password')");
        if($result) {
            RespData::success('添加管理员成功！');
        } else {
            RespData::error('对不起，添加管理员失败！');
        }
    }
    // 删除某一条记录数据
    public function deletebyid($id) {
        return parent::deletebyid($this->tbname, $id);
    }
    // 删除某些记录数据
    public function deletebyids($ids) {
        return parent::deletebyids($this->tbname, $ids);
    }
    // 更改管理员用户名
    public function updateName($username, $id) {
        $result = mysqli_query($this->conn, "update $this->tbname set username = $username where id = '$id'");
        if($result) {
            RespData::success('更改管理员用户名成功！');
        } else {
            RespData::error('对不起，更改管理员用户名失败！');
        }
    }
    // 更改管理员密码
    public function updatePwd($password, $id) {
        $result = mysqli_query($this->conn, "update $this->tbname set password = $password where id = '$id'");
        if($result) {
            RespData::success('更改密码成功！');
        } else {
            RespData::error('对不起，更改密码失败！');
        }
    }
    // 查询所有用户记录
    public function queryAll() {
        return parent::queryAll($this->tbname);
    }
    // 查询id对应的记录
    public function queryById($id) {
        return parent::queryById($this->tbname, $id);
    }
    // 管理员登录
    public function login($username, $password) {
        $result = mysqli_query($this->conn, "select * from $this->tbname where username = '$username' and password = '$password'");
        if ($result->num_rows > 0) {
            mysqli_free_result($result);
            RespData::success('登录成功！');
        } else {
            RespData::error('登录失败，请检查用户名和密码是否正确！');
        }
    }
}
