<?php
include_once './Mysql.php';

header('Content-Type:text/html;charset=utf-8');

  $mysql = new Mysql();
  $mysql->insert('wxid11', 1.65, 22, 11, '00121314141', '2019-03-29 15:48:14');
?>