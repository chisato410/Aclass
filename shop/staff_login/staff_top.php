<?php
session_start();
session_regenerate_id(true);
if (empty($_SESSION['login'])) {
  print 'ログインしていません<br>';
  print '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
  exit();
}
?>
<!doctype html>
<html lang="ja">

<head>
  <?php include '../common/head.php'; ?>
  <title>ショップ管理トップメニュー | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <h2 class="main__heading">ショップ管理トップメニュー</h2>

      <p class="main__login-user">
        ようこそ、<?php echo htmlspecialchars($_SESSION['staff_name'], ENT_QUOTES, 'UTF-8'); ?>さん
      </p>

      <div class="main__btns">
        <a href="../staff/staff_list.php" class="main__btn">スタッフ管理</a>
        <a href="../product/pro_list.php" class="main__btn">商品管理</a>
        <a href="../order/order_download.php" class="main__btn">注文ダウンロード</a>

        <!-- POSTでのログアウト推奨 -->
        <form action="staff_logout.php" method="post" style="display:inline;">
          <button type="submit" class="main__btn">ログアウトする</button>
        </form>
      </div>
    </div>
  </main>
</body>

</html>