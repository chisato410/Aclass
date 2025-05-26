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
  <title>商品一覧 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php
      try {
        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT code,name,price FROM mst_product WHERE 1';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();

        $dbh = null;
      } catch (Exception $e) {
        echo '<div class="form__error">';
        echo '<p>ただいま障害により大変ご迷惑をお掛けしております。</p>';
        echo '<p>' . $e->getMessage() . '</p>';
        echo '</div>';
        exit();
      }
      ?>

      <div class="form__container">
        <h2 class="form__title">商品一覧</h2>

        <form method="post" action="pro_branch.php" class="form__list">
          <?php while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
            <label class="radioItem">
              <input class="radioButton" type="radio" name="procode" value="<?php echo htmlspecialchars($rec['code']); ?>">
              <?php echo htmlspecialchars($rec['name']); ?> --- <?php echo htmlspecialchars($rec['price']); ?>円
            </label>
          <?php endwhile; ?>

          <div class="form-actions">
            <input type="submit" value="参照" name="disp" class="sub__btn">
            <input type="submit" value="追加" name="add" class="sub__btn">
            <input type="submit" value="修正" name="edit" class="sub__btn">
            <input type="submit" value="削除" name="delete" class="sub__btn">
          </div>
        </form>

        <div class="form-actions">
          <a href="../staff_login/staff_top.php" class="link-back">トップメニューへ</a>
        </div>
      </div>
    </div>
  </main>
</body>

</html>