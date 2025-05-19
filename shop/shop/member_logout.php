<?php
session_start();
$_SESSION = [];
if (isset($_COOKIE[session_name()]) == true) {
  setcookie(session_name(), '', time() - 42000, '/');
  session_destroy();
}
?>
<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <title>ログアウト</title>
</head>

<body>
  ログアウトしました。
  <br>
  <a href="shop_list.php">商品一覧へ</a>
</body>

</html>