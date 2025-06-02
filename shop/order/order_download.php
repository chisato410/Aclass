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
  <?php include '../common/head.php'; ?>
  <title>注文ダウンロード | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <div class="form-container">
        <h2 class="form__heading">注文データ ダウンロード</h2>

        <form method="post" action="order_download_done.php" class="form">
          <div class="form-group2">
            <?php pulldown_month(); ?>
            <span>月</span>
            <?php pulldown_day(); ?>
            <span>日</span>
          </div>

          <div class="form__actions">
            <input type="submit" class="main__submit" value="ダウンロードへ">
          </div>
        </form>
      </div>
    </div>
  </main>
</body>

</html>