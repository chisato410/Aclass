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
  <title>商品一覧 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php
      try {
        require_once('../common/common.php');
        $post = sanitize($_POST);
        $pro_name = $post['name'];
        $pro_price = $post['price'];
        $pro_gazou_name = $post['gazou_name'];

        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'INSERT INTO mst_product(name, price, gazou) VALUES (?, ?, ?)';
        $stmt = $dbh->prepare($sql);
        $data = [];
        $data[] = $pro_name;
        $data[] = $pro_price;
        $data[] = $pro_gazou_name;
        $stmt->execute($data);

        $dbh = null;

        echo '<div class="form__result">';
        echo '<p class="result">' . htmlspecialchars($pro_name, ENT_QUOTES, 'UTF-8') . ' を追加しました。</p>';
        echo '</div>';
      } catch (Exception $e) {
        echo '<div class="form__error">';
        echo '<p>ただいま障害により大変ご迷惑をお掛けしております。</p>';
        echo '<p>' . $e->getMessage() . '</p>';
        echo '</div>';
        exit();
      }
      ?>

      <div class="form-actions">
        <a href="pro_list.php" class="link-back">戻る</a>
      </div>
    </div>
  </main>
</body>

</html>