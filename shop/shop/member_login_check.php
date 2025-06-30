<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>
</head>

<body>
  <?php
  try {
    $member_email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $member_pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $member_pass = md5($member_pass);

    // データベースへ接続する
    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8mb4';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // データを表示する
    $sql = 'SELECT code,name FROM dat_member WHERE email=? AND password=?';
    $stmt = $dbh->prepare($sql);
    $data = [$member_email, $member_pass];
    $stmt->execute($data);

    $dbh = null;

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rec == false) {
      print 'メールアドレスかパスワードが間違っています。<br>';
      print '<a href="member_login.html">戻る</a>';
    } else {
      session_start();
      $_SESSION['member_login'] = 1;
      $_SESSION['member_code'] = $rec['code'];
      $_SESSION['member_name'] = $rec['name'];
      header('Location: shop_list.php');
      exit();
    }
  } catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をおかけしております。';
    print $e->getMessage(); // エラーメッセージを出す
    exit();
  }
  ?>

</body>

</html>