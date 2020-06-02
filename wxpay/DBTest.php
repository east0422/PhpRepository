<?php
require_once 'WXPayDBApi.php';

header('Content-Type:text/html;charset=utf-8');

  $wxPayDBApi = new WXPayDBApi();
  $wxPayDBApi->add('20200411163546', 100, 'NATIVE', 'wx111111111111111', '1216751521201407033233368018', '20141030133525');
?>