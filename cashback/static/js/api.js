// 引入api.js前需引入jquery.min.js
// 使用发送短信或短信验证引入api.js前需引入md5.min.js
// 使用cookie操作需提前引入jquery.cookie.js

// api请求地址
var baseUrl = 'http://localhost/req.php';
// 获取短信验证码及验证地址
var verifyUrl = 'http://verifyUrl/ApiMessage/';

const signKey = 'cashback';

// post请求
function $post(action, params) {
  params['action'] = action;
  return new Promise(function (resolve, reject) {
    $.ajax({
      url: baseUrl,
      type: 'POST',
      contentType: 'application/x-www-form-urlencoded',
      data: params,
      success: function (resp) {
        if (!resp) {
          reject('对不起，没找到对应返回值！');
        } else {
          resolve(JSON.parse(resp));
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
        if (!resp) {
          reject('对不起，没找到对应返回值！');
        } else {
          resolve(JSON.parse(resp));
        }
      },
      error: function (error) {
        reject(error);
      }
    });
  });
}

// 获取手机验证码
function getVerifyCode(phone, areacode) {
  return new Promise(function (resolve, reject) {
    $.ajax({
      url: verifyUrl,
      data: {
        type: 3,
        mobile: phone,
        msg_id: 624784,
        area: areacode,
        sign: md5(signKey + phone + signKey)
      },
      type: 'POST',
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
      url: verifyUrl,
      data: {
        type: 4,
        mobile: phone,
        code: code,
        sign: md5(signKey + phone + signKey)
      },
      type: 'POST',
      success: function (res) {
        resolve(res);
      },
      error: function (err) {
        reject(err);
      }
    });
  });
}

// 查询相关信息
function queryByMsg(msg) {
  var user = $.cookie('cookie_uid');
  return $post('query', {
    msg: msg,
    user: user,
    inviter: ''
  });
}

