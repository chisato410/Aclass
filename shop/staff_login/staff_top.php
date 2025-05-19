<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['login']) == false) {
  print 'ログインしていません<br>';
  print '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
  exit();
}
?>
<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <title>ショップ管理トップメニュー</title>
</head>

<body>
  ショップ管理トップメニュー
  <br>
  <br>
  <a href="../staff/staff_list.php">スタッフ管理</a>
  <br>
  <a href="../product/pro_list.php">商品管理</a>
  <br>
  <a href="../order/order_download.php">注文ダウンロード</a>
  <br>
  <a href="staff_logout.php">ログアウトする</a>
</body>

</html>