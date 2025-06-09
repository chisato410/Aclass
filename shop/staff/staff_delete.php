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
  <title>スタッフ削除 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php
      try {
        if (!isset($_GET['staffcode']) || !is_numeric($_GET['staffcode'])) {
          throw new Exception('不正なスタッフコードです。');
        }

        $staff_code = $_GET['staffcode'];

        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT name FROM mst_staff WHERE code = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$staff_code]);

        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$rec) {
          throw new Exception('スタッフが見つかりません。');
        }
        $staff_name = $rec['name'];

        $dbh = null;
      } catch (Exception $e) {
        echo '<div class="form__error">';
        echo '<p>エラーが発生しました：</p>';
        echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
        echo '</div>';
        exit();
      }
      ?>

      <div class="form__container">
        <h2 class="form__heading">スタッフ削除</h2>

        <div class="form__info">
          <p><strong>スタッフコード：</strong><?php echo htmlspecialchars($staff_code, ENT_QUOTES, 'UTF-8'); ?></p>
          <p><strong>スタッフ名：</strong><?php echo htmlspecialchars($staff_name, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <p class="form__confirm">このスタッフを削除してよろしいですか？</p>

        <form action="staff_delete_done.php" method="post" class="form-actions">
          <input type="hidden" name="code" value="<?php echo htmlspecialchars($staff_code, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="button" class="link-back" value="戻る" onclick="history.back()">
          <input type="submit" class="main__submit" value="OK">
        </form>
      </div>
    </div>
  </main>
</body>

</html>