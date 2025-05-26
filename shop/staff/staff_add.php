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
  <title>スタッフ追加 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <h2 class="form__title">スタッフ追加</h2>

      <form method="post" action="staff_add_check.php" class="form">

        <div class="form-group">
          <label for="name">スタッフ名を入力してください。</label><br>
          <input type="text" name="name" id="name" class="form__input" style="width: 200px;">
        </div>

        <div class="form-group">
          <label for="pass">パスワードを入力してください。</label><br>
          <input type="password" name="pass" id="pass" class="form__input" style="width: 100px;">
        </div>

        <div class="form-group">
          <label for="pass2">パスワードをもう１度入力してください。</label><br>
          <input type="password" name="pass2" id="pass2" class="form__input" style="width: 100px;">
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