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
        $staff_name = trim($post['name']);
        $staff_pass_plain = $post['pass'];

        // パスワードのハッシュ化（PASSWORD_DEFAULTはアルゴリズム変更時も安全）
        $staff_pass_hashed = password_hash($staff_pass_plain, PASSWORD_DEFAULT);

        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'UPDATE mst_staff SET name=?, password=? WHERE code=?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$staff_name, $staff_pass_hashed, $staff_code]);

        $dbh = null;
      } catch (Exception $e) {
        echo '<div class="form__error">';
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