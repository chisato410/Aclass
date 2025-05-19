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
  <title> ろくまる農園 商品情報参照</title>
</head>

<body>

  <?php

  print $member_message;


  try {
    $pro_code = filter_input(INPUT_GET, 'procode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // データベースへ接続する
    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8mb4';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // データを表示する
    $sql = 'SELECT name,price,gazou FROM mst_product WHERE code=?';
    $stmt = $dbh->prepare($sql);
    $data = [$pro_code];
    $stmt->execute($data);

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    $pro_name = $rec['name'];
    $pro_price = $rec['price'];
    $pro_gazou_name = $rec['gazou'];
    $dbh = null;

    $disp_gazou = '';
    if ($pro_gazou_name) {
      $gazou_file = '../product/gazou/' . $pro_gazou_name;
      $disp_gazou = '<img src="' . $gazou_file . '">';
    }
    print '<a href="shop_cartin.php?procode=' . $pro_code . '">カートに入れる</a>';
    print '<br><br>';
  } catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をおかけしております。';
    print $e->getMessage(); // エラーメッセージを出す
    exit();
  }
  ?>

  商品情報参照
  <br>
  <br>
  商品コード
  <br>
  <?php print $pro_code; ?>
  <br>
  <br>
  商品名
  <br>
  <?php print $pro_name; ?>
  <br>
  <br>
  価格
  <br>
  <?php print $pro_price; ?>
  円
  <br>
  <br>
  <?php print $disp_gazou; ?>
  <br>
  <form>
    <input type="button" onclick="history.back()" value="戻る">
  </form>
</body>

</html>