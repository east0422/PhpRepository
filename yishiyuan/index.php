<?php
  header('Content-type:text/html;charset=utf-8');
  
  session_start();

  $sessioninfo = $_POST['sessioninfo'];
  if (isset($sessioninfo)) {
    $_SESSION['sessioninfo'] = $sessioninfo;
  } 

  // 检测是否登录，若没有登录则跳转到登录页面
  if (!isset($_SESSION['sessioninfo'])) {
    header('Location:login.php');
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
    
    <link href="./static/css/common.css" rel="stylesheet">

    <script type="text/javascript" src="./layui/layui.js"></script>
    <script type="text/javascript" src="./static/js/jquery.min.js"></script>
    <!-- 解决Promise未定义  -->
    <script type="text/javascript" src="./static/js/bluebird.min.js"></script>
    <script type="text/javascript" src="./static/js/api.js"></script>
    <script type="text/javascript" src="./static/js/utils.js"></script>
  </head>
  <body>
    <div class="ysy-title">用户列表</div>
    <!-- 搜索区域 -->
    <div class="layui-search">
      <div class="layui-inline">
        <label class="layui-form-label">姓名</label>
        <div class="layui-input-inline" style="width: 100px;">
          <input class="layui-input" name="searchname" id="searchname" />
        </div>
      </div>
      <div class="layui-inline">
        <label class="layui-form-label">手机号</label>
        <div class="layui-input-inline" style="width: 100px;">
          <input class="layui-input" name="searchphone" id="searchphone" />
        </div>
      </div>
      <button class="layui-btn" id="search">搜索</button>
    </div>

    <table class="layui-hide" id="userlistTable" lay-filter="userlistTable"></table>
     
    <!-- 顶部工具集 -->
    <script type="text/html" id="userlisttoolbar">
      <div class="layui-btn-container">
        <button class="layui-btn layui-btn-sm layui-btn-radius" lay-event="delSelectUsers">
          <i class="layui-icon">&#xe640;</i>删除选中数据
        </button>
        <button class="layui-btn layui-btn-sm layui-btn-radius layui-btn-normal" lay-event="addUser">
          <i class="layui-icon">&#xe608;</i>新增用户
        </button>
      </div>
    </script>

    <!-- 编辑删除等操作 -->
    <script type="text/html" id="userlistbar">
      <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
      <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
      
    <script type="text/html" id="sexTpl">
      {{#  if(d.sex === '女'){ }}
        <span style="color: #F581B1;">{{ d.sex }}</span>
      {{#  } else { }}
        {{ d.sex }}
      {{#  } }}
    </script>

    <script>
    layui.use(['table', 'layer', 'jquery'], function() {
      var table = layui.table,
        layer = layui.layer,
        $$ = layui.jquery;

      var users = []; // 用户数据列表
      var userTable; // 操作用户数据表

      // 头工具栏事件
      table.on('toolbar(userlistTable)', function(obj){
        var checkStatus = table.checkStatus(obj.config.id);
        switch(obj.event){
          case 'delSelectUsers':
            var data = checkStatus.data;
            if (data.length < 1) {
              layer.msg('您当前未选中任何数据！', {time: 1000});
            } else {
              var uids = data.map(function(item) {
                return item.id
              });
              userDeleteByIds(uids).then(function(resp) { // 批量删除用户数据
                users = users.filter(function(item) {
                  return !uids.includes(item.id);
                });
                userTable.reload({
                  data: users
                });
              }, function(error) {
                console.log('error:', error);
                layer.msg('对不起，批量删除失败！', {time: 2000});
              });
            }
            break;
          case 'addUser':
            jumpToUserAdd();
            break;
        };
      });
  
      // 监听行操作工具(编辑/删除)事件
      table.on('tool(userlistTable)', function(obj) {
        var data = obj.data;
        if(obj.event === 'del'){
          layer.confirm('真的要删除' + data.name + '吗？', function(index) {
            layer.close(index);
            userDeleteById(data.id).then(function(resp) { // 删除一条用户数据
              console.log('del resp:', resp);
              obj.del();
            }, function(error) {
              layer.msg('对不起，删除' + data.name + '失败', {time: 2000});
            });
          });
        } else if(obj.event === 'edit') {
          jumpToUserEdit(JSON.stringify(data));
        }
      });

      var initOptions = {
        elem: '#userlistTable',
        title: '用户列表',
        id: 'userlistReload',
        toolbar: '#userlisttoolbar',
        skin: 'rows',
        even: true,
        page: true,
        limits: [10, 30, 50, 100],
        limit: 10,
        loading: false,
        cols: [[
          {type: 'checkbox', fixed: 'left', align: 'center', rowspan: 2},
          {type: 'numbers', title: '序号', align: 'center', rowspan: 2},
          {field: 'id', title: 'ID', align: 'center', hide: true, rowspan: 2},
          {field: 'name', title: '姓名', align: 'center', sort: true, rowspan: 2},
          {field: 'sfz', title: '身份证号', align: 'center', width: 180, hide: true, rowspan: 2},
          {field: 'sex', title: '性别', align: 'center', sort: true, templet: '#sexTpl', width: 80, rowspan: 2},
          {field: 'height', title: '身高(厘米)', align: 'center', width: 80, rowspan: 2},
          {field: 'marry', title: '婚姻', align: 'center', sort: true, width: 100, rowspan: 2},
          {field: 'birthday', title: '出生日期', align: 'center', sort: true, rowspan: 2},
          {field: 'education', title: '学历', align: 'center', sort: true, width: 80, rowspan: 2},
          {field: 'workyears', title: '工作年限(年)', align: 'center', width: 100, rowspan: 2},
          {field: 'salary', title: '月收入', align: 'center', sort: true, width: 150, rowspan: 2},
          {field: 'phone', title: '手机号', align: 'center', width: 120, rowspan: 2},
          {title: '地址', align: 'center', colspan: 4},
          {fixed: 'right', title: '操作', toolbar: '#userlistbar', align: 'center', width: 150, rowspan: 2}
        ], [
          {field: 'province', title: '省', align: 'center'},
          {field: 'city', title: '市', align: 'center'},
          {field: 'district', title: '县/区', align: 'center'},
          {field: 'address', title: '详细地址', align: 'center'},
        ]]
      };
      table.set(initOptions);

      userList().then(function(resp) {
        users = resp.data;
        userTable = table.render({
          data: users
        });
      }, function(error) {
        layer.msg('对不起，查询数据失败！', {time: 1000});
      });

      $$('#search').click(function() {
        var searchname = $$('#searchname').val();
        var searchphone = $$('#searchphone').val();
        queryUserBy(searchname, searchphone).then(function(resp) {
          users = resp.data;
          userTable = table.render({
            data: users
          });
        }, function(error) {
          console.log('search error:', error);
          layer.msg('对不起，搜索数据失败！', {time: 1000});
        });
      });
    });
    </script>
  </body>
</html>