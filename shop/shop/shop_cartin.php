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
  <title>商品カート追加 | ろくまる農園</title>
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

        if (!$pro_code) {
          echo '<p>不正なアクセスです。</p>';
          echo '<a href="shop_list.php" class="link-back">商品一覧に戻る</a>';
          exit();
        }

        if (isset($_SESSION['cart'])) {
          $cart = $_SESSION['cart'];
          $kazu = $_SESSION['kazu'];

          if (in_array($pro_code, $cart)) {
            echo '<p>その商品はすでにカートに入っています。</p>';
            echo '<a href="shop_list.php" class="link-back">商品一覧に戻る</a>';
            exit();
          }
        } else {
          $cart = [];
          $kazu = [];
        }

        $cart[] = $pro_code;
        $kazu[] = 1;
        $_SESSION['cart'] = $cart;
        $_SESSION['kazu'] = $kazu;
      } catch (Exception $e) {
        echo '<p>ただいま障害によりご迷惑をおかけしております。</p>';
        exit();
      }
      ?>

      <p>カートに追加しました。</p>
      <a href="shop_list.php" class="main__btn">商品一覧に戻る</a>
      <br><br>
      <a href="shop_cartlook.php" class="sub__btn">カートを見る</a>
    </div>
  </main>
</body>

</html>