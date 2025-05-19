<?php
/*session_start();
session_regenerate_id(true);
if (isset($_SESSION['login']) == false) {
  print 'ログインされていません。<br>';
  print '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
  exit();
} else {
  print $_SESSION['staff_name'];
  print 'さんがログイン中 <br> ';
  print '<br>';
}*/
?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ろくまる農園</title>
</head>

<body>
  <?php
  require_once('../common/common.php');
  $post = sanitize($_POST);

  try {
    $staff_code = $post['code'];
    $staff_pass = $post['pass'];

    $staff_pass = md5($staff_pass); // パスワードをMD5でハッシュ化

    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT name FROM mst_staff WHERE code=? AND password=?';
    $stmt = $dbh->prepare($sql);

    $data = array(); // ←配列を初期化
    $data[] = $staff_code;
    $data[] = $staff_pass;
    $stmt->execute($data);

    $dbh = null;

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rec == false) {
      print 'スタッフコードかパスワードが間違っています。<br>';
      print '<a href="staff_login.html">戻る</a>';
    } else {
      session_start();
      $_SESSION['login'] = 1;
      $_SESSION['$staff_code'] = $staff_code;
      $_SESSION['$staff_name'] = $rec['name'];
      header('Location:staff_top.php');
      exit();
    }
  } catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑お掛けしております。';
    print $e->getMessage();
    exit();
  }
  ?>
</body>

</html>