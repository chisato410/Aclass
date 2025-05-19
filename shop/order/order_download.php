<?php
require_once('../common/common.php');
?>

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
  注文ダウンロード
  <br>
  <br>
  <form method="post" action="order_download_done.php">
    <?php
    pulldown_year();
    ?>
    年
    <?php
    pulldown_month();
    ?>
    月
    <?php
    pulldown_day();
    ?>
    日
    <br>
    <input type="submit" value="ダウンロードへ">
  </form>

</body>

</html>