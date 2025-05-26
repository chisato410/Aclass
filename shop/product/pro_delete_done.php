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
  <title>商品削除実行 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php
      try {
        $pro_code = $_POST['code'];
        $pro_gazou_name = $_POST['gazou_name'];

        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'DELETE FROM mst_product WHERE code=?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$pro_code]);

        $dbh = null;

        if (!empty($pro_gazou_name)) {
          unlink('./gazou/' . $pro_gazou_name);
        }
      } catch (Exception $e) {
        echo '<div class="form__error">';
        echo '<p>ただいま障害により大変ご迷惑をお掛けしております。</p>';
        echo '<p>' . $e->getMessage() . '</p>';
        echo '</div>';
        exit();
      }
      ?>

      <div class="form-container">
        <p class="form__message">削除しました。</p>
        <a class="link-back" href="pro_list.php">戻る</a>
      </div>
    </div>
  </main>
</body>

</html>