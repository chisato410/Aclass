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
  <title>商品追加 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <h2 class="form__title">商品追加</h2>

      <form method="post" action="pro_add_check.php" enctype="multipart/form-data" class="form">

        <div class="form-group">
          <label for="name">商品名を入力してください。</label><br>
          <input type="text" name="name" id="name" class="form__input" style="width: 200px;">
        </div>

        <div class="form-group">
          <label for="price">価格を入力してください。</label><br>
          <input type="number" name="price" id="price" class="form__input" style="width: 100px;">
        </div>

        <div class="form-group">
          <label for="gazou">画像を選んでください。</label><br>
          <input type="file" name="gazou" id="gazou" class="form__input" style="width: 400px;">
        </div>

        <div class="form-actions">
          <input type="button" onclick="history.back()" value="戻る" class="link-back">
          <input type="submit" value="OK" class="main__submit">
        </div>

      </form>
    </div>
  </main>
</body>

</html>