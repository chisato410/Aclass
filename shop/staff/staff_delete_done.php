<?php
session_start();
session_regenerate_id(true);

if (!isset($_SESSION['login'])) {
  echo 'ログインしていません<br>';
  echo '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
  exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php include '../common/head.php'; ?>
  <title>スタッフ削除完了 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php
      try {
        if (!isset($_POST['code']) || !is_numeric($_POST['code'])) {
          throw new Exception('不正なリクエストです。');
        }

        $staff_code = $_POST['code'];

        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'DELETE FROM mst_staff WHERE code = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$staff_code]);

        $dbh = null;
      } catch (Exception $e) {
        echo '<div class="form__error">';
        echo '<p>エラーが発生しました：</p>';
        echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
        echo '</div>';
        exit();
      }
      ?>

      <div class="form-container">
        <p class="form__message">スタッフを削除しました。</p>
        <a class="link-back" href="staff_list.php">スタッフ一覧へ戻る</a>
      </div>
    </div>
  </main>
</body>

</html>