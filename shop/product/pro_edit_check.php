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
  <title>商品変更確認 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php
      require_once('../common/common.php');

      $post = sanitize($_POST);

      $pro_code = $post['code'];
      $pro_name = $post['name'];
      $pro_price = $post['price'];
      $pro_gazou_name_old = $post['gazou_name_old'];
      $pro_gazou = $_FILES['gazou'];

      $ok_flag = true;

      // 商品名チェック
      if ($pro_name === '') {
        echo '<p class="form__error">※ 商品名が入力されていません。</p>';
        $ok_flag = false;
      } else {
        echo '<p>商品名：' . htmlspecialchars($pro_name) . '</p>';
      }

      // 価格チェック（半角数字のみ）
      if (!preg_match('/\A[0-9]+\z/', $pro_price)) {
        echo '<p class="form__error">※ 価格は半角数字で入力してください。</p>';
        $ok_flag = false;
      } else {
        echo '<p>価格：' . htmlspecialchars($pro_price) . '円</p>';
      }

      // 画像チェック
      if ($pro_gazou['size'] > 0) {
        if ($pro_gazou['size'] > 1000000) {
          echo '<p class="form__error">※ 画像が大きすぎます（1MB以下にしてください）</p>';
          $ok_flag = false;
        } else {
          move_uploaded_file($pro_gazou['tmp_name'], './gazou/' . $pro_gazou['name']);
          echo '<p>画像プレビュー：</p>';
          echo '<img src="./gazou/' . htmlspecialchars($pro_gazou['name']) . '" alt="アップロード画像" style="max-width:200px;"><br>';
        }
      } else {
        // 画像を新しく選ばなかった場合は既存画像のまま
        $pro_gazou['name'] = $pro_gazou_name_old;
      }

      // フォーム表示
      if (!$ok_flag) {
        echo '<div class="form__actions">';
        echo '<input type="button" onclick="history.back()" value="戻る">';
        echo '</div>';
      } else {
        echo '<p class="form__confirm">上記の内容で修正します。</p>';
        echo '<form method="post" action="pro_edit_done.php" class="form__main">';
        echo '<input type="hidden" name="code" value="' . htmlspecialchars($pro_code) . '">';
        echo '<input type="hidden" name="name" value="' . htmlspecialchars($pro_name) . '">';
        echo '<input type="hidden" name="price" value="' . htmlspecialchars($pro_price) . '">';
        echo '<input type="hidden" name="gazou_name_old" value="' . htmlspecialchars($pro_gazou_name_old) . '">';
        echo '<input type="hidden" name="gazou_name" value="' . htmlspecialchars($pro_gazou['name']) . '">';
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