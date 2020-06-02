<?php
  header("Content-type:text/html; charset=utf-8");

  session_start();
  // 检测登录是否过期，若登录未过期则跳转到主页面
  if (isset($_SESSION['sessioninfo'])) {
    header('Location:index.php');
    exit();
  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>伊世缘</title>
  <!-- load css -->
  <link rel="stylesheet" type="text/css" href="./layui/css/layui.css" media="screen and (min-width:769px)">
  <!-- mobile -->
  <link rel="stylesheet" href="./layui/css/layui.mobile.css" media="screen and (max-width:768px)">
  
  <link rel="stylesheet" type="text/css" href="./static/css/login.css">

  <script type="text/javascript" src="./layui/layui.js"></script>
  <script type="text/javascript" src="./static/js/jquery.min.js"></script>
  <script type="text/javascript" src="./static/js/jquery.particleground.min.js"></script>
  <!-- 解决Promise未定义  -->
  <script type="text/javascript" src="./static/js/bluebird.min.js"></script>
  <script type="text/javascript" src="./static/js/api.js"></script>
  <script type="text/javascript" src="./static/js/utils.js"></script>
</head>
<body>
  <div class="login-content">
    <div class="login-title">后台管理系统</div>
    <div class="layui-user-icon larry-login">
      <input type="text" class="login_txtbx" name="username" id="username" placeholder="请输入用户名" />
    </div>
    <div class="layui-pwd-icon larry-login">
      <input type="password" class="login_txtbx" name="password" id="password" autocomplete="off" placeholder="请输入密码" />
    </div>
    <div class="layui-submit larry-login">
      <input type="button" name="submit" value="立即登录" class="submit_btn" id="login" />
    </div>
    <div class="layui-login-text">
      <p>© 2020-2021 伊世缘 版权所有</p>
    </div>
  </div>

  <script type="text/javascript">
    layui.use(['layer', 'jquery'], function() {
      var layer = layui.layer,
        $$ = layui.jquery;

      $$('body').particleground({
        dotColor: '#E8DFE8',
        lineColor: '#133b88'
      });

      $$('#password').on('keydown', function (event) {
        if (event.keyCode == 13) { // 回车键
          $$('#login').trigger('click');
        }
      });

      // 监听登录按钮点击
      $$('#login').click(function() {
        var username = $$('#username').val();
        var password = $$('#password').val();

        if (username == '') {
          layer.msg('用户名不能为空！', {
            icon: 5,
            time: 1000
          });
          return false;
        } else if (password == '') {
          layer.msg('密码不能为空！', {
            icon: 5,
            time: 1000
          });
          return false;
        }

        login(username, password).then(function(res) {
          layer.msg(res.message, {
            icon: 6,
            time: 1000
          });
          loginSuccess('xxxaahiuhiuahfahfai121218iu');
          return true;
        }, function(error) {
          layer.msg('对不起，登录失败！', {
            icon: 5,
            time: 2000
          });
        });
      });
    });
  </script>
</body>
</html>