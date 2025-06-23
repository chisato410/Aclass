<?php
session_start();
session_regenerate_id(true);
?>
<!doctype html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>数量変更エラー | ろくまる農園</title>
  <?php include '../common/head.php'; ?>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php
      $max = filter_input(INPUT_POST, 'max', FILTER_VALIDATE_INT);
      if ($max === false || $max < 0) {
        echo '<p class="error-message">不正なアクセスです。</p>';
        echo '<a href="shop_cartlook.php" class="link-back">カートに戻る</a>';
        exit();
      }
      $kazu = [];
      for ($i = 0; $i < $max; $i++) {
        $input_kazu = filter_input(INPUT_POST, 'kazu' . $i, FILTER_SANITIZE_NUMBER_INT);
        if (!preg_match('/\A[0-9]+\z/', $input_kazu)) {
          echo '<p class="error-message">数量に誤りがあります。</p>';
          echo '<a href="shop_cartlook.php" class="link-back">カートに戻る</a>';
          exit();
        }
        if ($input_kazu < 1 || $input_kazu > 10) {
          echo '<p class="error-message">数量は必ず1～10個までです。</p>';
          echo '<a href="shop_cartlook.php" class="link-back">カートに戻る</a>';
          exit();
        }
        $kazu[] = $input_kazu;
      }
      $cart = $_SESSION['cart'];
      for ($i = $max - 1; $i >= 0; $i--) {
        $sakujo = filter_input(INPUT_POST, 'sakujo' . $i, FILTER_DEFAULT);
        if ($sakujo !== null) {
          array_splice($cart, $i, 1);
          array_splice($kazu, $i, 1);
        }
      }
      $_SESSION['cart'] = $cart;
      $_SESSION['kazu'] = $kazu;
      header('Location: shop_cartlook.php');
      exit();
      ?>
    </div>
  </main>
</body>

</html>