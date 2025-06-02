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
  <title>スタッフ追加実行 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php
      try {
        require_once('../common/common.php');
        $post = sanitize($_POST);
        $staff_name = $post['name'];
        $staff_pass = password_hash($post['pass'], PASSWORD_DEFAULT); // セキュアなハッシュ化

        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'INSERT INTO mst_staff(name,password) VALUES (?,?)';
        $stmt = $dbh->prepare($sql);
        $data = [$staff_name, $staff_pass];
        $stmt->execute($data);

        $dbh = null;

        echo '<div class="form__result">';
        echo '<p class="result">' . htmlspecialchars($staff_name, ENT_QUOTES, 'UTF-8') . ' さんを追加しました。</p>';
        echo '</div>';
      } catch (Exception $e) {
        echo '<div class="form__error">';
        echo '<p>ただいま障害により大変ご迷惑をお掛けしております。</p>';
        // error_log($e->getMessage()); // 本番運用時はこちらだけ
        echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>'; // 開発中のみ
        echo '</div>';
        exit();
      }
      ?>

      <div class="form-actions">
        <a href="staff_list.php" class="link-back">戻る</a>
      </div>
    </div>
  </main>
</body>

</html>