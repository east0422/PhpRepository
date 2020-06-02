<?php

header('Content-type:text/html;charset=utf-8');
  $sys = $_GET['sys'];
  $type = $_GET['type'];
  $invite = $_GET['inviter'];
  $qq = $_GET['qq'];
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>LayIM测试</title>
  <!-- web(not mobile) -->
  <link rel="stylesheet" href="./layui/css/layui.css" media="screen and (min-width:769px)">
  <link rel="stylesheet" href="./layui/css/modules/layim/layim.css" media="screen and (min-width:769px)">

  <!-- mobile -->
  <link rel="stylesheet" href="./layui/css/layui.mobile.css" media="screen and (max-width:768px)">
  <link rel="stylesheet" href="./layui/css/modules/layim/mobile/layim.css" media="screen and (max-width:768px)">
  
  <!-- 解决Promise未定义  -->
  <script src="./static/js/bluebird.min.js" type="text/javascript"></script>
  
  <script src="./layui/layui.js" type="text/javascript"></script>

  <script src="./static/js/jquery.min.js" type="text/javascript"></script>
  <!-- api请求，依赖jquery，在jquery后加载 -->
  <script src="./static/js/api.js" type="text/javascript"></script>

  <!-- 公用css -->
  <link rel="stylesheet" href="./static/css/common.css">
</head>
<body>

<script>
  (/iphone|ipod|ipad/i.test(navigator.appVersion)) && document.addEventListener('blur', (e) => {
    // 这里加了个类型判断，因为a等元素也会触发blur事件
    ['input', 'textarea'].includes(e.target.localName) && document.body.scrollIntoView(false);
  }, true)
</script>
<script>

  function initLayim(layim) {
    layim.config({
      init: {
        mine: {
          username: '我自己',
          id: 100,
          avatar: './layui/images/mine.png'
        }
      },
      brief: true,
      initSkin: '4.jpg'
    });

    var chatList = {
      name: '云机器人',
      type: 'friend',
      avatar: './layui/images/client.png',
      id: 9999,
    };
    layim.chat(chatList);

    //监听发送消息
    layim.on('sendMessage', function(data){
      var To = data.to;
      
      if(To.type != 'mine') { // 回复
        getReply('<?php echo $sys; ?>', '<?php echo $qq; ?>', '<?php echo $inviter; ?>', data.mine.content).then(function(resp) {
          layim.getMessage({
            username: To.name,
            avatar: To.avatar,
            id: To.id,
            type: To.type,
            content: resp
          });
        }, function(error) {
          layim.getMessage({
            username: To.name,
            avatar: To.avatar,
            id: To.id,
            type: To.type,
            content: '对不起，网络请求错误！'
          });
        });
      }
    });
  }

  var device = layui.device();
  if (device.ios || device.android || device.weixin) { // 移动端
    layui.use('mobile', function() {
      initLayim(layui.mobile.layim);
    });
  } else {
    layui.use('layim', function() {
      initLayim(layui.layim);
    });
  }
</script>
</body>
</html>