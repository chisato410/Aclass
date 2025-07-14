<?php
session_start();
session_regenerate_id(true);

if (!isset($_SESSION['member_login'])) {
  $member_message = <<<HTML
ようこそ、ゲスト様
<a href="member_login.html" class="login__btn">会員ログイン</a><br><br>
HTML;
} else {
  $name = htmlspecialchars($_SESSION['member_name'], ENT_QUOTES, 'UTF-8');
  $member_message = <<<HTML
ようこそ、{$name}様
<a href="member_logout.php" class="login__btn">ログアウト</a><br><br>
HTML;
}
?>
<!doctype html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>商品情報参照 | ろくまる農園</title>
  <?php include '../common/head.php'; ?>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php echo $member_message; ?>

      <?php
      try {
        $pro_code = filter_input(INPUT_GET, 'procode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8mb4';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT name, price, gazou FROM mst_product WHERE code = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$pro_code]);

        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$rec) {
          echo '<p>商品情報が見つかりません。</p>';
          exit();
        }

        $pro_name = htmlspecialchars($rec['name'], ENT_QUOTES, 'UTF-8');
        $pro_price = htmlspecialchars($rec['price'], ENT_QUOTES, 'UTF-8');
        $pro_gazou_name = $rec['gazou'];

        $disp_gazou = '';
        if ($pro_gazou_name) {
          $gazou_file = '../product/gazou/' . htmlspecialchars($pro_gazou_name, ENT_QUOTES, 'UTF-8');
          $disp_gazou = '<img src="' . $gazou_file . '" alt="" style="max-width:200px; height:auto;">';
        }

        $dbh = null;
      } catch (Exception $e) {
        echo '<p>ただいま障害によりご迷惑をおかけしております。</p>';
        exit();
      }
      ?>

      <p>商品情報参照</p>
      <table border="1" class="cart-table">
        <tr>
          <th>商品コード</th>
          <td><?php echo htmlspecialchars($pro_code, ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
        <tr>
          <th>商品名</th>
          <td><?php echo $pro_name; ?></td>
        </tr>
        <tr>
          <th>価格</th>
          <td><?php echo $pro_price; ?>円</td>
        </tr>
        <tr>
          <th>画像</th>
          <td>
            <div class="gazou_wrap"><?php echo $disp_gazou; ?></div>
          </td>
        </tr>
      </table>

      <br>
      <a href="shop_cartin.php?procode=<?php echo urlencode($pro_code); ?>" class="main__btn">カートに入れる</a>
      <br><br>
      <a href="javascript:history.back()" class="sub__btn">戻る</a>
    </div>
  </main>
</body>

</html>