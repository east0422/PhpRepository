<?php
  header('Content-type:text/html;charset=utf-8');
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <script type="text/javascript" src="./static/js/jquery.min.js"></script>
  <script type="text/javascript" src="./static/js/jquery.cookie.js"></script>
  <script type="text/javascript">
    var uid = $.cookie('cookie_uid');
    if (uid) {
      window.location.href = 'main.php';
    } else {
      window.location.href = 'login.php';
    }
  </script>
</head>
<body>
</body>
</html>