// api请求地址
var baseUrl = 'http://localhost/yishiyuan/api/Api.php';
// 获取短信验证码及验证地址
var verifyUrl = 'http://localhost/reqOtherApi/index.php';

// post请求
function $post(action, params) {
  params['action'] = action;
  return new Promise(function (resolve, reject) {
    $.ajax({
      url: baseUrl,
      type: 'POST',
      data: params,
      success: function (resp) {
        var respData = JSON.parse(resp);
        if (respData.status == 200) {
          resolve(respData);
        } else {
          reject(respData);
        }
      },
      error: function (error) {
        reject(error);
      }
    });
  });
}

// get请求
function $get(action, params) {
  return new Promise(function (resolve, reject) {
    $.ajax({
      url: baseUrl + '?action=' + action,
      type: 'GET',
      data: params,
      success: function (resp) {
        var respData = JSON.parse(resp);
        if (respData.status == 200) {
          resolve(respData);
        } else {
          reject(respData);
        }
      },
      error: function (error) {
        reject(error);
      }
    });
  });
}

// 获取手机验证码
function getVerifyCode(phone) {
  return new Promise(function (resolve, reject) {
    $.ajax({
      url: verifyUrl + '?type=5&mobile=' + phone,
      type: 'GET',
      success: function (res) {
        resolve(res);
      },
      error: function (err) {
        reject(err);
      }
    });
  });
}

// 验证手机验证码
function checkVerifyCode(phone, code) {
  return new Promise(function (resolve, reject) {
    $.ajax({
      url: verifyUrl + '?type=6&mobile='+ phone + '&code=' + code,
      type: 'GET',
      success: function (res) {
        resolve(res);
      },
      error: function (err) {
        reject(err);
      }
    });
  });
}

/*** 管理员api ***/
// 管理员登录
function login(username, password) {
  return $get('login', {
    username: username,
    password: password
  });
}


/*** 用户api ***/
// 查询所有用户列表
function userList() {
  return $get('userlist', {});
}

// 依据uid查询用户数据
function queryUserById(uid) {
  return $get('queryuserbyid', {
    uid: uid
  });
}

// 查询满足条件的用户数据
function queryUserBy(searchname, searchphone) {
  return $get('queryuserby', {
    name: searchname,
    phone: searchphone
  });
}

// 添加用户
function userAdd(userParams) {
  return $post('useradd', userParams);
}

// 删除某一个用户数据
function userDeleteById(uid) {
  return $post('userdeletebyid', {
    uid: uid
  });
}

// 批量删除用户数据
function userDeleteByIds(uids) {
  return $post('userdeletebyids', {
    uids: uids
  });
}

// 修改用户
function userUpdate(userParams) {
  return $post('userupdate', userParams);
}
