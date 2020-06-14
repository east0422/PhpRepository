/** 依赖jquery，使用前需先引入jquery.js */

'use strict';

// 使用post进行跳转及传参
function jumpAndParams(path, paramname, paramvalue) {
  var jumpForm = $('<form>');
  jumpForm.attr('style', 'display:none');
  jumpForm.attr('target', '');
  jumpForm.attr('method', 'post');
  // 请求地址
  jumpForm.attr('action', path);
  var infoInput = $('<input>');
  infoInput.attr('type', 'hidden');
  infoInput.attr('name', paramname);
  infoInput.attr('value', paramvalue);
  $('body').append(jumpForm);
  jumpForm.append(infoInput);
  jumpForm.submit();
  jumpForm.remove();
}

// 登录成功后跳转
function loginSuccess() {
  jumpAndParams('main.php', '', '');
}

function logout() {
  jumpAndParams('login.php', '', '');
}
