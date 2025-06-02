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
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const pass1 = document.getElementById('pass');
      const pass2 = document.getElementById('pass2');
      const submitBtn = document.querySelector('.main__submit');

      function validatePasswords() {
        if (pass1.value !== pass2.value) {
          pass2.setCustomValidity('パスワードが一致しません');
        } else {
          pass2.setCustomValidity('');
        }
      }

      pass1.addEventListener('input', validatePasswords);
      pass2.addEventListener('input', validatePasswords);
    });
  </script>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <h2 class="form__title">スタッフ追加</h2>

      <form method="post" action="staff_add_check.php" class="form">

        <div class="form-group">
          <label for="name">スタッフ名を入力してください。</label>
          <input type="text" name="name" id="name" class="form__input" style="width: 200px;" required>
        </div>

        <div class="form-group">
          <label for="pass">パスワードを入力してください。</label>
          <input
            type="password"
            name="pass"
            id="pass"
            class="form__input"
            style="width: 100px;"
            autocomplete="off"
            required
            pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$"
            title="8文字以上の英字と数字を含めてください">
        </div>

        <div class="form-group">
          <label for="pass2">パスワードをもう１度入力してください。</label>
          <input
            type="password"
            name="pass2"
            id="pass2"
            class="form__input"
            style="width: 100px;"
            autocomplete="off"
            required>
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