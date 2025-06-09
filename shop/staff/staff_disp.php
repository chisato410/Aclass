<?php
session_start();
session_regenerate_id(true);

// セッションチェック（ログイン確認だけ、表示は後で）
if (empty($_SESSION['login'])) {
  echo 'ログインされていません。<br>';
  echo '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
  exit();
}

$staff_name = $_SESSION['staff_name'];
// パラメータチェック
if (!isset($_GET['staffcode']) || !preg_match('/^[0-9]+$/', $_GET['staffcode'])) {
  echo '不正なアクセスです。<br>';
  echo '<a href="staff_list.php">スタッフ一覧へ戻る</a>';
  exit();
}

$staff_code = $_GET['staffcode'];

try {
  // DB接続
  $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
  $user = 'root';
  $password = 'root';
  $dbh = new PDO($dsn, $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // スタッフ名取得
  $sql = 'SELECT name FROM mst_staff WHERE code = ?';
  $stmt = $dbh->prepare($sql);
  $data = [$staff_code];
  $stmt->execute($data);

  $rec = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$rec) {
    echo '該当スタッフが見つかりませんでした。<br>';
    exit();
  }

  $staff_name = $rec['name'];
  $dbh = null;
} catch (Exception $e) {
  echo 'ただいま障害によりご迷惑をおかけしております。';
  // echo $e->getMessage(); // 開発中は表示してもよいが、本番環境では非表示推奨
  exit();
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php include '../common/head.php'; ?>
  <title>スタッフ情報参照 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">

    <div class="main__inner">
      <h2 class="main__heading">スタッフ情報参照</h2>

      <dl class="staff-item">
        <dt class="staff-name">スタッフコード</dt>
        <dd class="staff-content"><?= htmlspecialchars($staff_code, ENT_QUOTES, 'UTF-8'); ?></dd>
      </dl>

      <dl class="staff-item">
        <dt class="staff-name">スタッフ名</dt>
        <dd class="staff-content"><?= htmlspecialchars($staff_name, ENT_QUOTES, 'UTF-8'); ?></dd>
      </dl>

      <form>
        <input type="button" value="戻る" onclick="history.back()" class="link-back">
      </form>
    </div>
  </main>
</body>

</html>