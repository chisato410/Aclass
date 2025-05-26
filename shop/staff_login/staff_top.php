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
  <?php include '../common/head.php'; ?>
  <title>ショップ管理トップメニュー | ろくまる農園</title>
</head>


<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <h2 class="main__heading">ショップ管理トップメニュー</h2>

      <div class="main__btns">
        <a href="../staff/staff_list.php" class="main__btn">スタッフ管理</a>
        <a href="../product/pro_list.php" class="main__btn">商品管理</a>
        <a href="../order/order_download.php" class="main__btn">注文ダウンロード</a>
        <a href="staff_logout.php" class="main__btn">ログアウトする</a>
      </div>
    </div>
  </main>
</body>

</html>