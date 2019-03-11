1、配置公众号回调地址，下载对应的MP_verify_xxxxx.txt添加到当前路径下。
2、添加微信支付商户允许访问ip地址，并下载cert替换当前路径下的cert。
3、修改代码信息
  3.1 api.php 修改post方法bindopenid接口的返回地址。
  3.2 Conf.php 修改MCHAPPID, MCHID, KEY, APPCERTPATH, APPKEYPATH。
  3.3 Mysql.php 修改数据库及表名，创建对应的数据库和表，修改正确的数据库用户名和密码。
  3.4 Utils.php 修改appid, appsecret。