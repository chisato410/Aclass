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
  <title>スタッフ修正確認 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php
      require_once('../common/common.php');

      $post = sanitize($_POST);
      $staff_code = $post['code'];
      $staff_name = trim($post['name']);
      $staff_pass = $post['pass'];
      $staff_pass2 = $post['pass2'];

      $has_error = false;

      echo '<div class="form-result">';

      // スタッフ名のバリデーション
      if ($staff_name === '') {
        echo '<p class="error">スタッフ名が入力されていません。</p>';
        $has_error = true;
      } else {
        echo '<p>スタッフ名：' . htmlspecialchars($staff_name, ENT_QUOTES, 'UTF-8') . '</p>';
      }

      // パスワードのバリデーション
      if ($staff_pass === '') {
        echo '<p class="error">パスワードが入力されていません。</p>';
        $has_error = true;
      }

      if ($staff_pass !== $staff_pass2) {
        echo '<p class="error">パスワードが一致しません。</p>';
        $has_error = true;
      }

      // エラーがある場合
      if ($has_error) {
        echo '<div class="form-actions">';
        echo '<input type="button" onclick="history.back()" value="戻る" class="link-back">';
        echo '</div>';
      } else {
        // 問題なし、確認フォーム表示
        echo '<form method="post" action="staff_edit_done.php">';
        echo '<input type="hidden" name="code" value="' . htmlspecialchars($staff_code, ENT_QUOTES, 'UTF-8') . '">';
        echo '<input type="hidden" name="name" value="' . htmlspecialchars($staff_name, ENT_QUOTES, 'UTF-8') . '">';
        echo '<input type="hidden" name="pass" value="' . htmlspecialchars($staff_pass, ENT_QUOTES, 'UTF-8') . '">';
        echo '<div class="form-actions">';
        echo '<input type="button" onclick="history.back()" value="戻る" class="link-back">';
        echo '<input type="submit" value="OK" class="main__submit">';
        echo '</div>';
        echo '</form>';
      }

      echo '</div>';
      ?>
    </div>
  </main>
</body>

</html>