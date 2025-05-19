<?php
session_start();
$_SESSION = [];
if (isset($_COOKIE[session_name()]) == true) {
  setcookie(session_name(), '', time() - 42000, '/');
  session_destroy();
}
?>
<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <title>カートを空にする</title>
</head>

<body>
  カートを空にしました。
</body>

</html>