<?php
  header('Content-type:text/html;charset=utf-8');
  
  session_start();

  // 若管理员已经登录会保留session，否则添加数据的话需要使用手机验证码
  $isAdmin = isset($_SESSION['sessioninfo']);

  $userinfo = $_POST['userinfo'];
  $isEdit = isset($userinfo); // 是否编辑用户(true为编辑，false为添加)
?>

<!DOCTYPE html>
<html lang="zh-cn">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <title>伊世缘</title>
  <link href="./layui/css/layui.css" rel="stylesheet">
  <link href="./static/css/common.css" rel="stylesheet">

  <script src="./layui/layui.js" type="text/javascript"></script>
  <script src="./static/js/jquery.min.js" type="text/javascript"></script>
  <!-- 地区，依赖jquery，在jquery后加载 -->
  <script src="./static/js/area.js" type="text/javascript"></script>
  <script src="./static/js/select.js" type="text/javascript"></script>
  <!-- 解决Promise未定义  -->
  <script src="./static/js/bluebird.min.js" type="text/javascript"></script>
  <!-- api请求，依赖jquery，在jquery后加载 -->
  <script src="./static/js/api.js" type="text/javascript"></script>
  <script type="text/javascript" src="./static/js/utils.js"></script>
  <!-- 自定义下拉选择框模版 -->
  <script id="selectoptions" type="text/html">
    <select name="{{d.name}}" lay-verify="required">
    {{# layui.each(d.list, function(index, item){ }}
      <option value="{{ item }}">
        {{ item + d.suffix}} 
      </option>
    {{# }); }}
    </select>
  </script>
</head>

<body>
  <div class="ysy-title"><?php echo ($isEdit ? '编辑用户' : '添加用户'); ?></div>
  <div class="layui-container">
    <form class="layui-form layui-form-pane" action="" lay-filter="userForm">
      <div class="layui-row">
        <div class="layui-col-xs* layui-input-block"></div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">姓名</label>
        <div class="layui-input-block">
          <input type="text" name="name" id="name" required lay-verify="required" lay-reqtext="请输入姓名" placeholder="请输入姓名" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item" pane>
        <label class="layui-form-label">性别</label>
        <div class="layui-input-block">
          <input type="radio" name="sex" value="男" title="男" checked>
          <input type="radio" name="sex" value="女" title="女">
        </div>
      </div>
      <div class="layui-form-item" pane>
        <label class="layui-form-label">婚姻</label>
        <div class="layui-input-block">
          <input type="radio" name="marry" value="未婚" title="未婚" checked>
          <input type="radio" name="marry" value="已婚" title="已婚">
          <input type="radio" name="marry" value="离异" title="离异">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">出生日期</label>
        <div class="layui-input-block">
          <input type="text" name="birthday" id="birthday" readonly="readonly" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">身份证</label>
        <div class="layui-input-block">
          <input type="text" name="sfz" id="sfz" required lay-verify="sfz" placeholder="请输入身份证号" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">身高</label>
        <div class="layui-input-block" id="heightoptions"></div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">学历</label>
        <div class="layui-input-block">
          <select name="education" lay-verify="required">
            <option value="小学">小学</option>
            <option value="初中">初中</option>
            <option value="高中">高中</option>
            <option value="中专">中专</option>
            <option value="大专">大专</option>
            <option value="本科">本科</option>
            <option value="研究生">研究生</option>
            <option value="博士">博士</option>
            <option value="保密">保密</option>
          </select>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">月收入</label>
        <div class="layui-input-block">
          <select name="salary" lay-verify="required">
            <option value="0~5000">0~5000</option>
            <option value="5000~10000">5000~10000</option>
            <option value="10000～20000">10000～20000</option>
            <option value="20000～50000">20000～50000</option>
            <option value="50000～100000">50000～100000</option>
            <option value="100000以上">100000以上</option>
            <option value="保密">保密</option>
          </select>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">工作年限</label>
        <div class="layui-input-block" id="workyearoptions">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">地区</label>
        <div class="layui-input-block" style="font-size:0.8em">
          <div class="layui-col-xs4">
            <select name="province" id="province" lay-filter="province">
              <option value="">省份</option>
            </select>
          </div>
          <div class="layui-col-xs4">
            <select name="city" id="city" lay-filter="city">
              <option value="">地级市</option>
            </select>
          </div>
          <div class="layui-col-xs4">
            <select name="district" id="district" lay-filter="district">
              <option value="">县/区</option>
            </select>
          </div>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">详细住址</label>
        <div class="layui-input-block">
          <input type="text" name="address" required lay-verify="required" placeholder="请输入详细住址" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">手机号</label>
        <div class="layui-input-block">
          <input type="text" name="phone" id="phone" required lay-verify="required|phone" lay-reqtext="请输入11位正确手机号" placeholder="请输入手机号" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-row" id="verifycodeShow">
        <div class="layui-col-xs8 layui-form-item">
          <label class="layui-form-label">验证码</label>
          <div class="layui-input-inline">
            <input type="text" name="verifycode" id="verifycode" required lay-verify="verifycode" placeholder="手机验证码" autocomplete="off" class="layui-input">
          </div>
        </div>
        <div class="layui-col-xs4"> <button class="layui-btn layui-btn-radius" id="getverifycode">获取验证码</button></div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <button class="layui-btn" lay-submit lay-filter="confirm">确定</button>
          <button type="reset" class="layui-btn layui-btn-normal" id="reset">重置</button>
          <button class="layui-btn layui-btn-normal" id="backBtn">返回</button>
        </div>
      </div>
    </form>
  </div>
  <script>
  layui.use(['form', 'layer', 'laydate', 'laytpl', 'jquery'], function() {
    var form = layui.form,
      layer = layui.layer,
      laydate = layui.laydate,
      laytpl = layui.laytpl,
      $$ = layui.jquery;

    var isAdmin = '<?php echo $isAdmin; ?>'; // 是否管理员
    var isEdit = '<?php echo $isEdit; ?>'; // 是否编辑
    var userData = '<?php echo $userinfo; ?>'; // 编辑用户时传递过来的用户数据

    // 日期
    laydate.render({
      elem: '#birthday'
    });

    // 使用自定义下拉框模块填充数据
    var getTpl = selectoptions.innerHTML;

    // 身高下拉框
    var heightList = [];
    for (var i = 140; i <= 190; i++) {
      heightList.push(i);
    }
    var heightoptionsData = {
      'name': 'height',
      'suffix': 'cm',
      'list': heightList
    };
    var heightoptionsView = $$('#heightoptions')[0];
    laytpl(getTpl).render(heightoptionsData, function(html) {
      heightoptionsView.innerHTML = html;
    });

    // 工作年限下拉框
    var workyearsList = [];
    for (var i = 1; i <= 30; i++) {
      workyearsList.push(i);
    }
    var workyearsData = {
      'name': 'workyears',
      'suffix': '年',
      'list': workyearsList
    };
    var workyearoptionsView = $$('#workyearoptions')[0]
    laytpl(getTpl).render(workyearsData, function(html) {
      workyearoptionsView.innerHTML = html;
    });

    if (isAdmin) { // 管理员登录，删除验证码操作
      $$('#verifycodeShow').remove();
    } else {
      $$('#backBtn').hide(); // 非管理员不需要返回按钮
    }

    // 编辑用户时获取传递过来的用户数据
    if (isEdit) { // 是否编辑
      $$('#reset').hide(); // 编辑时隐藏重置按钮

      userData = JSON.parse(userData);
      // 填充表单数据
      form.val('userForm', userData);

      // 编辑时为省市区赋值
      if (userData.province) {
        $$('#province').siblings('div.layui-form-select').find('dl dd[lay-value=' + userData.province + ']').click();
      }
      if (userData.city) {
        $$('#city').siblings('div.layui-form-select').find('dl dd[lay-value=' + userData.city + ']').click();
      }
      if (userData.district) {
        $$('#district').siblings('div.layui-form-select').find('dl dd[lay-value=' + userData.district + ']').click();
      }
    }

    // 填充数据后重新渲染
    form.render();

    // 自定义验证规则
    form.verify({
      sfz: [
        /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/,
        '请输入正确的身份证号码'
      ],
      verifycode: function(value, item) { // value：表单的值，item：表单的DOM对象
        if(!isAdmin && !(/^\d{6}$/.test(value))) { // 管理员不需要验证
          return '请输入6位数字验证码';
        }
      }
    });

    // 监听获取验证码按钮点击事件
    $$('#getverifycode').click(function() {
      var phone = $$('#phone').val();
      if (/^[1][3,4,5,7,8][0-9]{9}$/.test(phone)) {
        getVerifyCode(phone).then(function(resp) {
          var respData = JSON.parse(resp);
          var result = respData.result;
          if (result == false || result == '') {
            layer.msg('获取验证码失败，请输入11位正确手机号！', {
              icon: 5, // 不开心的表情
            });
          } else {
            layer.msg('获取验证码成功，请查看手机！', {
              icon: 6, // 笑脸
            });
          }
        }, function(error) {
         layer.msg('获取验证码失败' + error, {
            icon: 5,
          });
        });
      } else {
        layer.msg('请输入11位正确手机号', {
          icon: 5,
        });
      }
      return false;
    });
    $$('#backBtn').click(function() {
      jumpToUserList();
      return false;
    });

    // 添加用户
    window.addUser = function(userParams) {
      userAdd(userParams).then(function(resp) {
        console.log('add resp:', resp);
        if (isAdmin) {
          jumpToUserList();
        } else {
          layer.msg('添加用户成功', {
            icon: 6, // 笑脸
          });
        }
      }, function(error) {
        layer.msg('对不起，添加用户失败！', {
          icon: 5, // 不开心的表情
        });
      });
    }

    // 编辑用户
    window.editUser = function(userParams) {
      userParams.uid = userData.id;
      userUpdate(userParams).then(function(resp) {
        if (isAdmin) {
          jumpToUserList();
        } else {
          layer.msg('编辑用户成功', {
            icon: 6, // 笑脸
          });
        }
      }, function(error) {
        layer.msg('对不起，编辑用户失败！', {
          icon: 5, // 不开心的表情
        });
      });
    }

    // 依据isEdit判断当前操作为添加还是编辑
    window.confirmHandle = function(params) {
      if (isEdit) { // 编辑用户
        editUser(params);
      } else { // 添加用户
        addUser(params);
      }
    }

    // 监听提交
    form.on('submit(confirm)', function(data) {
      var params = data.field;
      if (isAdmin) { // 管理员不需要验证手机验证码
        confirmHandle(params);
      } else { // 非管理员需要验证手机验证码
        checkVerifyCode(params.phone, params.verifycode).then(function(resp) {
          var respData = JSON.parse(resp);
          if (respData.result == true) {
            confirmHandle(params);
          } else {
            layer.msg('对不起，验证码不对，请检查是否输入正确或重新获取！', {
              icon: 5,
            });
          }
        }, function(error) {
          layer.msg('对不起，验证码不对，请检查是否输入正确或重新获取！', {
            icon: 5,
          });
        });
      }

      return false;
    });
  });
  </script>
</body>

</html>