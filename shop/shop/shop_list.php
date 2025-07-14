<?php
session_start();
session_regenerate_id(true);

// 🔽 まずカートの個数を最初に定義しておく
if (isset($_SESSION['cart'])) {
  $cart = $_SESSION['cart'];
  $shop_items = count($cart);
} else {
  $shop_items = 0;
}


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
<html>

<head>
  <?php include '../common/head.php'; ?>
  <title>商品一覧 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
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

        print '<div class="product-list">';
        while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
          print '<a href="shop_product.php?procode=' . $rec['code'] . '" class="product-list__item">';
          print '<span class="product">' . htmlspecialchars($rec['name'], ENT_QUOTES, 'UTF-8') . '</span>';
          print '--- ';
          print htmlspecialchars($rec['price'], ENT_QUOTES, 'UTF-8') . '円';
          print '</a>';
        }
        print '</div>';
        if ($shop_items > 0) {
          $count_badge = '<span class="cart-count-badge">' . $shop_items . '</span>';
        } else {
          $count_badge = '';
        }
        print '<a href="shop_cartlook.php" class="cart-link">カートを見る ' . $count_badge . '</a>';
      } catch (Exception $e) {
        print 'ただいま障害により大変ご迷惑をお掛けしております。';
        exit();
      }
      ?>
    </div>
  </main>
</body>

</html>