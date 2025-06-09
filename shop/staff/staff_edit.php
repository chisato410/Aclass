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
  <title>スタッフ修正 | ろくまる農園</title>
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
          throw new Exception('スタッフ情報が見つかりません。');
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
        <h2 class="form__heading">スタッフ修正</h2>

        <div class="form__info">
          <p><strong>スタッフコード：</strong><?php echo htmlspecialchars($staff_code, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <form action="staff_edit_check.php" method="post">
          <input type="hidden" name="code" value="<?php echo htmlspecialchars($staff_code, ENT_QUOTES, 'UTF-8'); ?>">

          <div class="form-group">
            <label for="name">スタッフ名</label><br>
            <input type="text" id="name" name="name" style="width: 200px;" value="<?php echo htmlspecialchars($staff_name, ENT_QUOTES, 'UTF-8'); ?>">
          </div>

          <div class="form-group">
            <label for="pass">パスワードを入力してください</label><br>
            <input type="password" id="pass" name="pass" style="width: 100px;">
          </div>

          <div class="form-group">
            <label for="pass2">パスワードをもう一度入力してください</label><br>
            <input type="password" id="pass2" name="pass2" style="width: 100px;">
          </div>

          <div class="form-actions">
            <input class="link-back" type="button" value="戻る" onclick="history.back()">
            <input class="main__submit" type="submit" value="OK">
          </div>
        </form>
      </div>
    </div>
  </main>
</body>

</html>