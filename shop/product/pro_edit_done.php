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

        $pro_code = $post['code'];
        $pro_name = $post['name'];
        $pro_price = $post['price'];
        $pro_gazou_name_old = $post['gazou_name_old'];
        $pro_gazou_name = $post['gazou_name'];

        // DB接続
        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 更新SQL実行
        $sql = 'UPDATE mst_product SET name = ?, price = ?, gazou = ? WHERE code = ?';
        $stmt = $dbh->prepare($sql);
        $data = [$pro_name, $pro_price, $pro_gazou_name, $pro_code];
        $stmt->execute($data);

        $dbh = null;

        // 古い画像の削除
        if ($pro_gazou_name_old !== $pro_gazou_name && $pro_gazou_name_old !== '') {
          if (file_exists('./gazou/' . $pro_gazou_name_old)) {
            unlink('./gazou/' . $pro_gazou_name_old);
          }
        }

        echo '<p class="result">商品情報を修正しました。</p>';
      } catch (Exception $e) {
        echo '<p>ただいま障害によりご迷惑をお掛けしております。</p>';
        echo '<p class="error">' . htmlspecialchars($e->getMessage()) . '</p>';
        exit();
      }
      ?>

      <div class="form-actions">
        <a href="pro_list.php" class="link-back">商品一覧に戻る</a>
      </div>
    </div>
  </main>
</body>

</html>