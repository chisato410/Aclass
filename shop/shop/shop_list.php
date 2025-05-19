<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['member_login']) == false) {
  $member_message = 'ようこそ、ゲスト様 ';
  $member_message .= '<a href="member_login.html">会員ログイン</a>';
  $member_message .= '<br><br>';
} else {
  $member_message = 'ようこそ、';
  $member_message .= $_SESSION['member_name'];
  $member_message .= '様 ';
  $member_message .= '<a href="member_logout.php">ログアウト</a>';
  $member_message .= '<br><br>';
}
?>
<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <title> ろくまる農園 商品一覧</title>
</head>

<body>

  <?php

  print $member_message;

  try {

    // データベースへ接続する
    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8mb4';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // データを表示する
    $sql = 'SELECT code,name,price FROM mst_product WHERE 1';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $dbh = null;

    print '商品一覧<br><br>';

    while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
      print '<a href="shop_product.php?procode=' . $rec['code'] . '">';
      print $rec['name'];
      print '---';
      print $rec['price'];
      print '円';
      print '</a>';
      print '<br>';
    }
  } catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をおかけしております。';
    print $e->getMessage(); // エラーメッセージを出す
    exit();
  }
  ?>

  <br>
  <a href="shop_cartlook.php">カートを見る</a>
</body>

</html>