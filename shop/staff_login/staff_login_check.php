<?php
require_once('../common/common.php');
session_start();
session_regenerate_id(true);

try {
  $post = sanitize($_POST);
  $staff_code = $post['code'];
  $staff_pass = $post['pass'];

  $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
  $user = 'root';
  $password = 'root';
  $dbh = new PDO($dsn, $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = 'SELECT name, password FROM mst_staff WHERE code = ?';
  $stmt = $dbh->prepare($sql);
  $stmt->execute([$staff_code]);

  $rec = $stmt->fetch(PDO::FETCH_ASSOC);
  $dbh = null;

  if (!$rec || !password_verify($staff_pass, $rec['password'])) {
    $error_message = 'スタッフコードかパスワードが間違っています。';
  } else {
    $_SESSION['login'] = true;
    $_SESSION['staff_code'] = $staff_code;
    $_SESSION['staff_name'] = $rec['name'];
    header('Location:staff_top.php');
    exit();
  }
} catch (Exception $e) {
  $error_message = 'ただいま障害により大変ご迷惑をお掛けしております。';
  $error_detail = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php include '../common/head.php'; ?>
  <title>ログインチェック | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <h2>ログインエラー</h2>

      <?php if (isset($error_message)) : ?>
        <p><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><a href="staff_login.html" class="link-back">ログイン画面へ戻る</a></p>
      <?php endif; ?>

      <?php if (isset($error_detail)) : ?>
        <p class="error-detail"><?php echo htmlspecialchars($error_detail, ENT_QUOTES, 'UTF-8'); ?></p>
      <?php endif; ?>
    </div>
  </main>
</body>

</html>