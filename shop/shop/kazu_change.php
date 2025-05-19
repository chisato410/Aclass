<?php
session_start();
session_regenerate_id(true);


$max = filter_input(INPUT_POST, 'max', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

for ($i = 0; $i < $max; $i++) {
  $kazu[] = filter_input(INPUT_POST, 'kazu' . $i, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  // 入力値が数値のみかチェック
  if (preg_match('/\A[0-9]+\z/', $kazu[$i]) == 0) {
    print '数量に誤りがあります。<br>';
    print '<a href="shop_cartlook.php">カートに戻る</a>';
    exit();
  }

  // 1～10のみ許可
  if ($kazu[$i] < 1 || $kazu[$i] > 10) {
    print '数量は必ず1～10個までです。<br>';
    print '<a href="shop_cartlook.php">カートに戻る</a>';
    exit();
  }
}

$cart = $_SESSION['cart'];

for ($i = $max - 1; $i >= 0; $i--) {
  // 削除ボックスをチェックしている場合は取り除く
  $sakujo = filter_input(INPUT_POST, 'sakujo' . $i, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  if (isset($sakujo) == true) {
    array_splice($cart, $i, 1);
    array_splice($kazu, $i, 1);
  }
}

$_SESSION['cart'] = $cart;

$_SESSION['kazu'] = $kazu;

header('Location: shop_cartlook.php');
exit();
