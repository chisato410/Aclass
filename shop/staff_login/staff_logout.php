<?php
session_start();

// セッション変数をクリア
$_SESSION = [];

// セッションクッキーの削除
if (ini_get('session.use_cookies')) {
  $params = session_get_cookie_params();
  setcookie(
    session_name(),
    '',
    time() - 42000,
    $params['path'],
    $params['domain'],
    $params['secure'],
    $params['httponly']
  );
}

// セッション破棄
session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php include '../common/head.php'; ?>
  <title>ログアウト | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <p class="result">ログアウトしました。</p>
      <p><a href="../staff_login/staff_login.html" class="link-back">ログイン画面へ</a></p>
    </div>
  </main>
</body>

</html>