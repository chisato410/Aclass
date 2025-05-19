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
  <title> ろくまる農園 商品をカートに入れる</title>
</head>

<body>

  <?php

  print $member_message;


  try {
    $pro_code = filter_input(INPUT_GET, 'procode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (isset($_SESSION['cart']) == true) {
      // セッションから商品と個数を取得する
      $cart = $_SESSION['cart'];
      $kazu = $_SESSION['kazu'];

      // カートに既に入っている場合
      if (in_array($pro_code, $cart) == true) {
        print 'その商品はすでにカートに入っています。';
        print '<br><br>';
        print '<a href="shop_list.php">商品一覧に戻る</a>';
        exit();
      }
    }

    // 商品を追加する
    $cart[] = $pro_code;
    $_SESSION['cart'] = $cart;

    // 個数を追加する
    $kazu[] = 1;
    $_SESSION['kazu'] = $kazu;
  } catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をおかけしております。';
    print $e->getMessage(); // エラーメッセージを出す
    exit();
  }
  ?>

  カートに追加しました。
  <br>
  <br>
  <a href="shop_list.php">商品一覧に戻る</a>
</body>

</html>