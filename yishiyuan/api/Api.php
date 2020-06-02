<?php
include_once './UserApi.php';
include_once './AdminUserApi.php';
include_once './RespData.php';

// 指定允许其他域名访问，常用于调试
// header('Access-Control-Allow-Origin:*');

header('Content-Type:text/html;charset=utf-8');

// 用户api
$userApi = new UserApi();
// 管理员api
$adminUserApi = new AdminUserApi();

$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST') { // post请求
    $action = $_POST['action'];
    switch ($action) {
        case 'useradd': // 添加用户
            $name = $_POST['name'];
            $sex = $_POST['sex'];
            $marry = $_POST['marry'];
            $birthday = $_POST['birthday'];
            $sfz = $_POST['sfz'];
            $height = $_POST['height'];
            $education = $_POST['education'];
            $salary = $_POST['salary'];
            $workyears = $_POST['workyears'];
            $province = $_POST['province'];
            $city = $_POST['city'];
            $district = $_POST['district'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $userApi->add($name, $sex, $marry, $birthday, $sfz, $height, $education, $salary, $workyears, $province, $city, $district, $address, $phone);
            break;
        case 'userdeletebyid': // 删除用户
            $uid = $_POST['uid'];
            $userApi->deletebyid($uid);
            break;
        case 'userdeletebyids': // 批量删除用户
            $uids = $_POST['uids'];
            $uids = implode("','", $uids); // 数组拼接字符
            $userApi->deletebyids($uids);
            break;
        case 'userupdate': // 更改用户数据
            $name = $_POST['name'];
            $sex = $_POST['sex'];
            $marry = $_POST['marry'];
            $birthday = $_POST['birthday'];
            $sfz = $_POST['sfz'];
            $height = $_POST['height'];
            $education = $_POST['education'];
            $salary = $_POST['salary'];
            $workyears = $_POST['workyears'];
            $province = $_POST['province'];
            $city = $_POST['city'];
            $district = $_POST['district'];
            $address = $_POST['address'];
            $phone = $_POST['phone'];
            $uid = $_POST['uid'];
            $userApi->update($name, $sex, $marry, $birthday, $sfz, $height, $education, $salary, $workyears, $province, $city, $district, $address, $phone, $uid);
            break;
        default:
            RespData::error('post 请检查您的参数是否错误！');
            break;
    }
} else if ($method == 'GET') { // get请求
    $action = $_GET['action'];
    switch ($action) {
        case 'login': // 管理员登录
            $username = $_GET['username'];
            $password = $_GET['password'];
            $adminUserApi->login($username, $password);
            break;
        case 'userlist': // 查询所有用户列表
            $res = $userApi->queryAll();
            RespData::successData($res);
            break;
        case 'queryuserbyid': // 查询id对应用户详细信息
            $id = $_GET['id'];
            $res = $userApi->queryById($id);
            RespData::successData($res);
            break;
        case 'queryuserby': // 查询满足条件的用户信息
            $name = $_GET['name'];
            $phone = $_GET['phone'];
            $res = $userApi->queryBy($name, $phone);
            RespData::successData($res);
            break;
        default:
            RespData::error('get 请检查您的参数是否错误！');
            break;
    }
}

?>