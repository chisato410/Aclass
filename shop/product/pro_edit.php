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
  <title>商品修正 | ろくまる農園</title>
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

        $sql = 'SELECT name,price,gazou FROM mst_product WHERE code=?';
        $stmt = $dbh->prepare($sql);
        $data[] = $pro_code;
        $stmt->execute($data);
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        $pro_name = $rec['name'];
        $pro_price = $rec['price'];
        $pro_gazou_name_old = $rec['gazou'];

        $dbh = null;

        $disp_gazou = ($pro_gazou_name_old !== '')
          ? '<img src="./gazou/' . htmlspecialchars($pro_gazou_name_old) . '" alt="商品画像" style="max-width:200px;">'
          : '<p>画像は登録されていません。</p>';
      } catch (Exception $e) {
        echo '<div class="form__error">';
        echo '<p>ただいま障害により大変ご迷惑をおかけしております。</p>';
        echo '<p>' . $e->getMessage() . '</p>';
        echo '</div>';
        exit();
      }
      ?>

      <div class="form__container">
        <h2 class="form__title">商品修正</h2>

        <p>商品コード：<?php echo htmlspecialchars($pro_code); ?></p>

        <form action="pro_edit_check.php" method="post" enctype="multipart/form-data" class="form__main">
          <input type="hidden" name="code" value="<?php echo htmlspecialchars($pro_code); ?>">
          <input type="hidden" name="gazou_name_old" value="<?php echo htmlspecialchars($pro_gazou_name_old); ?>">

          <div class="form-group">
            <label for="name">商品名</label>
            <input type="text" name="name" id="name" style="width: 200px;" value="<?php echo htmlspecialchars($pro_name); ?>">
          </div>

          <div class="form-group">
            <label for="price">価格</label>
            <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($pro_price); ?>">円
          </div>

          <div class="form-group">
            <label>現在の画像</label><br>
            <?php echo $disp_gazou; ?>
          </div>

          <div class="form-group">
            <label for="gazou">画像を選んでください</label>
            <input type="file" name="gazou" id="gazou" style="width: 400px;">
          </div>

          <div class="form-actions">
            <input type="button" value="戻る" onclick="history.back()" class="link-back">
            <input type="submit" value="OK" class="main__submit">
          </div>
        </form>
      </div>
    </div>
  </main>
</body>

</html>