<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>购物返现</title>
  <!-- load css -->
  <link rel="stylesheet" type="text/css" href="./layui/css/layui.css">
  <link rel="stylesheet" type="text/css" href="./static/css/common.css"> 
  <link rel="stylesheet" type="text/css" href="./static/css/login.css">

  <script type="text/javascript" src="./layui/layui.js"></script>
  <script type="text/javascript" src="./static/js/jquery.min.js"></script>
  <script type="text/javascript" src="./static/js/jquery.cookie.js"></script>
  <script type="text/javascript" src="./static/js/jquery.particleground.min.js"></script>
  <!-- 解决Promise未定义  -->
  <script type="text/javascript" src="./static/js/bluebird.min.js"></script>

  <script type="text/javascript" src="./static/js/md5.min.js"></script>
  <script type="text/javascript" src="./static/js/api.js"></script>
  <script type="text/javascript" src="./static/js/utils.js"></script>
  <script type="text/javascript">
    var uid = $.cookie('cookie_uid');
    if (uid) { // 已登陆跳转到main.php
      window.location.href = 'main.php';
    }
  </script>
</head>
<body>
  <div class="login-content">
    <div class="login-title">用户登录</div>
    <form class="layui-form layui-form-pane hcenter" action="" lay-filter="userForm">
      <div class="layui-form-item hcontainer vcenter">
        <i class="layui-icon layui-icon-cellphone login-icon"></i>
        <div class="login-areacode">
          <select name="areacode" id="areacode" lay-verify="required">
            <option value="+86">+86 中国</option>
            <option value="+84">+84 越南</option>
          </select>
        </div>
        <input type="tel" name="phone" id="phone" required lay-verify="required|phone" placeholder="请输入手机号" autocomplete="off" class="layui-input">
      </div>
      <div class="layui-form-item hcontainer vcenter">
        <i class="layui-icon layui-icon-vercode login-icon"></i>
        <input type="text" name="verifycode" id="verifycode" required lay-verify="verifycode" placeholder="手机验证码" autocomplete="off" class="layui-input">
        <button class="layui-btn layui-btn-radius layui-btn-sm" id="getverifycode">获取验证码</button>
      </div>
      <div class="layui-form-item">
        <button class="layui-btn layui-btn-danger" lay-submit lay-filter="confirm">登录</button>
      </div>
    </form>
  </div>

  <script type="text/javascript">
    layui.use(['form', 'layer', 'jquery'], function() {
      var form = layui.form,
        layer = layui.layer,
        $$ = layui.jquery;

      var phoneReg = /^([1][3,4,5,7,8][0-9]{9})|(((1(2([0-9])|6([2-9])|88|99))|(9((?!5)[0-9])))([0-9]{7}))$/;

        // 自定义验证规则
      form.verify({
        phone: function(value, item) {
          if(!(phoneReg.test(value))) { // 中国大陆或越南
            return '请输入正确手机号码';
          }
        },
        verifycode: function(value, item) { // value：表单的值，item：表单的DOM对象
          if(!(/^(\d{4}|\d{6})$/.test(value))) { // 4或6位数字验证码
            return '请输入4或6位数字验证码';
          }
        }
      });

      $$('body').particleground({
        dotColor: '#E8DFE8',
        lineColor: '#133b88'
      });

      // 倒计时
      var countdown = 60;
      window.settime = function(val) {
        if (countdown == 0) {  
          val.removeAttribute('disabled');  
          val.innerHTML = '重新发送';  
          countdown = 60;  
          return false;  
        } else {  
          val.setAttribute('disabled', true);  
          val.innerHTML = countdown + '秒后重发';  
          countdown--;  
        }  
        setTimeout(function() {  
          settime(val);  
        }, 1000);  
      }

      // 监听获取验证码按钮点击事件
      $$('#getverifycode').click(function() {
        var areacode = $$('#areacode').val().substring(1);
        var phone = $$('#phone').val();
        var _this = this;
        if (phoneReg.test(phone)) {
          getVerifyCode(phone, areacode).then(function(resp) {
            settime(_this);
            var respData = JSON.parse(resp).data;
            var result = respData.result;
            if (result != 0) {
              layer.msg('获取验证码失败：' + respData.errmsg, {
                icon: 5, // 不开心的表情
                time: 2000,
              });
            }
          }, function(error) {
           layer.msg('获取验证码失败：', error, {
              icon: 5,
              time: 2000,
            });
          });
        } else {
          layer.msg('请输入正确手机号', {
            icon: 5,
            time: 2000,
          });
        }
        return false;
      });

      // 监听提交
      form.on('submit(confirm)', function(data) {
        var params = data.field;

        checkVerifyCode(params.phone, params.verifycode).then(function(resp) {
          var respData = JSON.parse(resp);
          if (respData.result != 'fail') {
            $$.cookie('cookie_uid', params.phone, {expires: 36500});
            loginSuccess();
          } else {
            layer.msg('对不起，验证码不对，请检查是否输入正确或重新获取！', {
              icon: 5,
              time: 2000,
            });
          }
        }, function(error) {
          layer.msg('对不起，验证码不对，请检查是否输入正确或重新获取！', {
            icon: 5,
            time: 2000,
          });
        });

        return false;
      });
    });
  </script>
</body>
</html>