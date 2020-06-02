// api请求地址
var baseUrl = '';

// post请求
function $post(params) {
  return new Promise(function (resolve, reject) {
    $.ajax({
      url: baseUrl,
      type: 'POST',
      data: params,
      success: function (res) {
        resolve(res);
      },
      error: function (res) {
        reject(res);
      }
    });
  });
}

// get请求
function $get(params) {
  return new Promise(function (resolve, reject) {
    $.ajax({
      url: baseUrl,
      type: 'GET',
      data: JSON.stringify(params),
      success: function (res) {
        resolve(res);
      },
      error: function (res) {
        reject(res);
      }
    });
  });
}

// 获取回复信息
function getReply(sys, qq, inviter, msg) {
  return new Promise(function (resolve, reject) {
    $.ajax({
      url: baseUrl + sys + '/index.php?qq=' + qq + '&inviter=' + inviter + '&msg=' + window.encodeURI(msg),
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
