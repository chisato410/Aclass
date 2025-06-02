<?php
session_start();

// セッション変数を空に
$_SESSION = [];

// セッションIDに関連付けられたクッキーの削除
if (isset($_COOKIE[session_name()])) {
  setcookie(session_name(), '', time() - 42000, '/');
}

// セッションの破棄
session_destroy();
?>

<head>
  <?php include '../common/head.php'; ?>
  <title>ログアウト | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>


  <main class="main">
    <main class="main__inner">
      <p class="result">ログアウトしました。</p>
      <p><a href="../staff_login/staff_login.html" class="link-back">ログイン画面へ</a></p>
    </main>
  </main>

</body>

</html>