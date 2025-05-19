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
  <title> ろくまる農園 商品カート</title>
</head>

<body>

  <?php

  print $member_message;


  try {
    if (isset($_SESSION['cart']) == true) {
      $cart = $_SESSION['cart'];
      $kazu = $_SESSION['kazu'];
      $max = count($cart);
    } else {
      $max = 0;
    }

    if ($max == 0) {
      print 'カートに商品が入っていません。';
      print '<br><br>';
      print '<a href="shop_list.php">商品一覧に戻る</a>';
      exit();
    }
    // データベースへ接続する
    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8mb4';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    foreach ($cart as $key => $val) {
      // データを表示する
      $sql = 'SELECT code,name,price,gazou FROM mst_product WHERE code=?';
      $stmt = $dbh->prepare($sql);
      $data[0] = $val;
      $stmt->execute($data);

      $rec = $stmt->fetch(PDO::FETCH_ASSOC);

      $pro_name[] = $rec['name'];
      $pro_price[] = $rec['price'];
      $pro_gazou_name = $rec['gazou'];

      // 画像があれば表示する
      if ($pro_gazou_name) {
        $gazou_file = '../product/gazou/' . $pro_gazou_name;
        $pro_gazou[] = '<img src="' . $gazou_file . '">';
      } else {
        $pro_gazou[] = '';
      }
    }

    $dbh = null;
  } catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をおかけしております。';
    print $e->getMessage(); // エラーメッセージを出す
    exit();
  }
  ?>
  カートの中は
  <br>
  <form method="post" action="kazu_change.php">
    <table border="1">
      <tr>
        <td>商品</td>
        <td>画像</td>
        <td>価格</td>
        <td>数量</td>
        <td>小計</td>
        <td>削除</td>
      </tr>
      <?php
      for ($i = 0; $i < $max; $i++) {
      ?>
        <tr>
          <td>
            <?php print $pro_name[$i]; ?>
          </td>
          <td>
            <?php print $pro_gazou[$i]; ?>
          </td>
          <td>
            <?php print $pro_price[$i] . '円'; ?>
          </td>
          <td>
            <?php print '<input type="number" name="kazu' . $i . '" value="' . $kazu[$i] . '">'; ?>
          </td>
          <td>
            <?php print $pro_price[$i] * $kazu[$i] . '円'; ?>
          </td>
          <td>
            <?php print '<input type="checkbox" name="sakujo' . $i . '">';
            ?>
          </td>
        </tr>
      <?php
      }
      ?>
    </table>
    <input type="hidden" name="max" value="<?php print $max; ?>">
    <input type="submit" value="数量変更">
    <br>
    <a href="shop_list.php">商品一覧に戻る</a>
  </form>

  <br>
  <a href="shop_form.html">購入手続きへ進む</a>
  <br>
  <?php
  if (isset($_SESSION["member_login"]) == true) {
    print '<a href="shop_kantan_check.php">会員かんたん注文へ進む</a>';
    print '<br>';
  }
  ?>
</body>

</html>