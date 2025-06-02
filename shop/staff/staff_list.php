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
  <title>スタッフ一覧 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <h2 class="main__heading">スタッフ一覧</h2>

      <?php
      try {
        // DB接続
        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // スタッフ情報取得
        $sql = 'SELECT code, name FROM mst_staff WHERE 1';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $dbh = null;

        echo '<form method="post" action="staff_branch.php" class="staff-form">';

        while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $code = htmlspecialchars($rec['code'], ENT_QUOTES, 'UTF-8');
          $name = htmlspecialchars($rec['name'], ENT_QUOTES, 'UTF-8');
          $id = 'staff_' . $code;

          echo '<div class="staff-item">';
          echo '<input type="radio" id="' . $id . '" name="staffcode" class="radioButton" value="' . $code . '">';
          echo '<label class="radioItem" for="' . $id . '">' . $name . '</label>';
          echo '</div>';
        }

        echo '<p class="form-note">スタッフを選択して操作を行ってください。</p>';

        echo '<div class="form-buttons">';
        echo '<input class="sub__btn" type="submit" value="参照" name="disp">';
        echo '<input class="sub__btn" type="submit" value="追加" name="add">';
        echo '<input class="sub__btn" type="submit" value="修正" name="edit">';
        echo '<input class="sub__btn" type="submit" value="削除" name="delete">';
        echo '</div>';

        echo '</form>';
      } catch (Exception $e) {
        echo 'ただいま障害により大変ご迷惑をお掛けしております。<br>';
        echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        exit();
      }
      ?>

      <br>
      <a href="../staff_login/staff_top.php" class="link-back">トップメニューへ</a>
    </div>
  </main>
</body>

</html>