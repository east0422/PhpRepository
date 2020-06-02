<?php
include_once './DBApi.php';
include_once './RespData.php';

header('content-type:text/html;charset=utf-8');

date_default_timezone_set('Etc/GMT-8');

/* 
 * 用户操作类(用户的增删改查)
 */
class UserApi extends DBApi {

    private $tbname = 'user'; // 用户表名
 
    // 依据字段插入数据表记录
    public function add($name, $sex, $marry, $birthday, $sfz, $height, $education, $salary, $workyears, $province, $city, $district, $address, $phone) {
        $stmt = $this->conn->prepare("insert into $this->tbname(name, sex, marry, birthday, sfz, height, education, salary, workyears, province, city, district, address, phone) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssssssss', $name, $sex, $marry, $birthday, $sfz, $height, $education, $salary, $workyears, $province, $city, $district, $address, $phone);
        $stmt->execute();
        $affectedRows = mysqli_affected_rows($this->conn);
        $stmt->close();
        if ($affectedRows > 0) {
            RespData::success('插入数据成功！');
        } else {
            RespData::error('对不起，插入数据失败！');
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
    // 更改某条数据
    public function update($name, $sex, $marry, $birthday, $sfz, $height, $education, $salary, $workyears, $province, $city, $district, $address, $phone, $id) {
        $stmt = $this->conn->prepare("update $this->tbname set name = ?, sex = ?, marry = ?, birthday = ?, sfz = ?, height = ?, education = ?, salary = ?, workyears = ?, province = ?, city = ?, district = ?, address = ?, phone = ? where id = ?");
        $stmt->bind_param('ssssssssssssssi', $name, $sex, $marry, $birthday, $sfz, $height, $education, $salary, $workyears, $province, $city, $district, $address, $phone, $id);
        $stmt->execute();
        $affectedRows = mysqli_affected_rows($this->conn);
        $stmt->close();
        if ($affectedRows > 0) {
            RespData::success('更改用户数据成功！');
        } else {
            RespData::error('对不起，更改用户数据失败！--'.$id.'==');
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
    // 查询满足条件的用户
    // TODO: 条件多的话拼接查询语句可以进一步优化简化
    public function queryBy($name, $phone) {
        $sqlStr = 'select * from '.$this->tbname;
        if (!empty($name)) {
            $sqlStr = $sqlStr.' where name like \'%'.$name.'%\'';
            if (!empty($phone)) {
                $sqlStr = $sqlStr.' and phone like \'%'.$phone.'%\'';
            }
        } else {
            if (!empty($phone)) {
                $sqlStr = $sqlStr.' where phone like \'%'.$phone.'%\'';
            }
        }

        return parent::queryBySql($sqlStr);
    }
}
