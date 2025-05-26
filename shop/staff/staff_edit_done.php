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
  <title>スタッフ修正完了 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php
      try {
        require_once('../common/common.php');
        $post = sanitize($_POST);
        $staff_code = $post['code'];
        $staff_name = $post['name'];
        $staff_pass = $post['pass'];

        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'UPDATE mst_staff SET name=?,password=? WHERE code=?';
        $stmt = $dbh->prepare($sql);
        $data = [$staff_name, $staff_pass, $staff_code];
        $stmt->execute($data);

        $dbh = null;
      } catch (Exception $e) {
        echo '<div class="error">';
        echo '<p>ただいま障害により大変ご迷惑をお掛けしております。</p>';
        echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
        echo '</div>';
        exit();
      }
      ?>

      <div class="result">
        <p>スタッフ情報を修正しました。</p>
        <div class="form-actions">
          <a href="staff_list.php" class="link-back">スタッフ一覧へ戻る</a>
        </div>
      </div>
    </div>
  </main>
</body>

</html>