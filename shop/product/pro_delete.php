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
  <title>商品削除 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php
      try {
        $pro_code = $_GET['procode'];

        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT name, gazou FROM mst_product WHERE code=?';
        $stmt = $dbh->prepare($sql);
        $data[] = $pro_code;
        $stmt->execute($data);

        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        $pro_name = $rec['name'];
        $pro_gazou_name = $rec['gazou'];

        $dbh = null;

        $disp_gazou = ($pro_gazou_name != '') ? '<img src="./gazou/' . htmlspecialchars($pro_gazou_name, ENT_QUOTES, 'UTF-8') . '">' : '';
      } catch (Exception $e) {
        echo '<div class="form__error">';
        echo '<p>ただいま障害により大変ご迷惑をおかけしております。</p>';
        echo '<p>' . $e->getMessage() . '</p>';
        echo '</div>';
        exit();
      }
      ?>

      <div class="form__container">
        <h2 class="form__heading">商品削除</h2>

        <div class="form__info">
          <p><strong>商品コード：</strong><?php echo htmlspecialchars($pro_code, ENT_QUOTES, 'UTF-8'); ?></p>
          <p><strong>商品名：</strong><?php echo htmlspecialchars($pro_name, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php if ($disp_gazou): ?>
            <div class="form__image"><?php echo $disp_gazou; ?></div>
          <?php endif; ?>
        </div>

        <p class="form__confirm">この商品を削除してよろしいですか？</p>

        <form action="pro_delete_done.php" method="post" class="form-actions">
          <input type="hidden" name="code" value="<?php echo htmlspecialchars($pro_code, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="gazou_name" value="<?php echo htmlspecialchars($pro_gazou_name, ENT_QUOTES, 'UTF-8'); ?>">
          <input type="button" class="link-back" value="戻る" onclick="history.back()">
          <input type="submit" class="main__submit" value="OK">
        </form>
      </div>
    </div>
  </main>
</body>

</html>