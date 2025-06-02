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
  <title>スタッフ追加確認 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main>
    <div class="main__inner">
      <?php
      require_once('../common/common.php');
      $post = sanitize($_POST);

      $staff_name = $post['name'];
      $staff_pass = $post['pass'];
      $staff_pass2 = $post['pass2'];

      echo '<div class="form__result">';

      $hasError = false;

      // スタッフ名確認
      if ($staff_name == '') {
        echo '<p class="form__error">スタッフ名が入力されていません。</p>';
        $hasError = true;
      } else {
        echo '<p>スタッフ名：' . htmlspecialchars($staff_name, ENT_QUOTES, 'UTF-8') . '</p>';
      }

      // パスワード確認
      if ($staff_pass == '') {
        echo '<p class="form__error">パスワードが入力されていません。</p>';
        $hasError = true;
      }

      if ($staff_pass != $staff_pass2) {
        echo '<p class="form__error">パスワードが一致しません。</p>';
        $hasError = true;
      }

      echo '</div>';

      if ($hasError) {
        echo '<div class="form__actions">';
        echo '<form>';
        echo '<input type="button" onclick="history.back()" value="戻る" class="link-back">';
        echo '</form>';
        echo '</div>';
      } else {
        $staff_pass = md5($staff_pass);
        echo '<form method="post" action="staff_add_done.php" class="form">';
        echo '<input type="hidden" name="name" value="' . htmlspecialchars($staff_name, ENT_QUOTES, 'UTF-8') . '">';
        echo '<input type="hidden" name="pass" value="' . $staff_pass . '">';
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