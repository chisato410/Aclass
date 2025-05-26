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
  <title>商品追加確認 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php
      require_once('../common/common.php');
      $post = sanitize($_POST);

      $pro_name = $post['name'];
      $pro_price = $post['price'];
      $pro_gazou = $_FILES['gazou'];

      echo '<div class="form__result">';

      $hasError = false;

      // 商品名の確認
      if ($pro_name === '') {
        echo '<p class="form__error">商品名が入力されていません。</p>';
        $hasError = true;
      } else {
        echo '<p>商品名：' . htmlspecialchars($pro_name, ENT_QUOTES, 'UTF-8') . '</p>';
      }

      // 価格の確認
      if (!preg_match('/\A[0-9]+\z/', $pro_price)) {
        echo '<p class="form__error">価格をきちんと入力してください。</p>';
        $hasError = true;
      } else {
        echo '<p>価格：' . htmlspecialchars($pro_price, ENT_QUOTES, 'UTF-8') . '円</p>';
      }

      // 画像の確認
      if ($pro_gazou['size'] > 0) {
        if ($pro_gazou['size'] > 1000000) {
          echo '<p class="form__error">画像が大きすぎます（1MBまで）。</p>';
          $hasError = true;
        } else {
          move_uploaded_file($pro_gazou['tmp_name'], './gazou/' . $pro_gazou['name']);
          echo '<p>画像：<br><img src="./gazou/' . htmlspecialchars($pro_gazou['name'], ENT_QUOTES, 'UTF-8') . '" alt="" style="max-width:200px;"></p>';
        }
      }

      echo '</div>';

      // 戻る or 送信フォーム
      if ($hasError) {
        echo '<div class="form__actions">';
        echo '<form>';
        echo '<input type="button" onclick="history.back()" value="戻る" class="link-back">';
        echo '</form>';
        echo '</div>';
      } else {
        echo '<p>上記の商品を追加します。</p>';
        echo '<form method="post" action="pro_add_done.php" class="form">';
        echo '<input type="hidden" name="name" value="' . htmlspecialchars($pro_name, ENT_QUOTES, 'UTF-8') . '">';
        echo '<input type="hidden" name="price" value="' . htmlspecialchars($pro_price, ENT_QUOTES, 'UTF-8') . '">';
        echo '<input type="hidden" name="gazou_name" value="' . htmlspecialchars($pro_gazou['name'], ENT_QUOTES, 'UTF-8') . '">';
        echo '<div class="form-actions">';
        echo '<input type="button" onclick="history.back()" value="戻る" class="link-back">';
        echo '<input type="submit" value="OK" class="main__submit">';
        echo '</div>';
        echo '</form>';
      }
      ?> </div>
  </main>
</body>

</html>