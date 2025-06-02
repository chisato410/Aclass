<?php
session_start();
session_regenerate_id(true);
if (!isset($_SESSION['login'])) {
  echo 'ログインしていません<br>';
  echo '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
  exit();
}
?>
<!doctype html>
<html lang="ja">

<head>
  <?php include '../common/head.php'; ?>
  <title>注文ダウンロード | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>


  <main class="main">
    <div class="main__inner">
      <?php
      $year  = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $month = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $day   = filter_input(INPUT_POST, 'day', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $y_m_d = $year . '-' . $month . '-' . $day;
      try {
        // DB接続
        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8mb4';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // SQLクエリ
        $sql = '
            SELECT
                dat_sales.code,
                dat_sales.date,
                dat_sales.code_member,
                dat_sales.name AS dat_sales_name,
                dat_sales.email,
                dat_sales.postal1,
                dat_sales.postal2,
                dat_sales.address,
                dat_sales.tel,
                dat_sales_product.code_product,
                mst_product.name AS mst_product_name,
                dat_sales_product.price,
                dat_sales_product.quantity
            FROM
                dat_sales
            JOIN dat_sales_product ON dat_sales.code = dat_sales_product.code_sales
            JOIN mst_product ON dat_sales_product.code_product = mst_product.code
            WHERE
                substr(dat_sales.date, 1, 10) = ?
        ';
        $stmt = $dbh->prepare($sql);
        $stmt->execute([$y_m_d]);
        $dbh = null;
        // CSV生成
        $csv = "注文コード,注文日時,会員番号,お名前,メール,郵便番号,住所,TEL,商品コード,商品名,価格,数量\n";
        while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $csv .= $rec['code'] . ',';
          $csv .= $rec['date'] . ',';
          $csv .= $rec['code_member'] . ',';
          $csv .= $rec['dat_sales_name'] . ',';
          $csv .= $rec['email'] . ',';
          $csv .= $rec['postal1'] . '-' . $rec['postal2'] . ',';
          $csv .= $rec['address'] . ',';
          $csv .= $rec['tel'] . ',';
          $csv .= $rec['code_product'] . ',';
          $csv .= $rec['mst_product_name'] . ',';
          $csv .= $rec['price'] . ',';
          $csv .= $rec['quantity'] . "\n";
        }
        // SJISに変換して書き込み
        $file = fopen('./chumon.csv', 'w');
        $csv = mb_convert_encoding($csv, 'SJIS', 'UTF-8');
        fputs($file, $csv);
        fclose($file);
      } catch (Exception $e) {
        echo 'ただいま障害により大変ご迷惑をおかけしております。<br>';
        echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'); // セキュリティ対策
        exit();
      }
      ?>
      <div class="date-select-wrapper">
        <p><a href="chumon.csv" class="sub__btn sub2__btn">注文データのCSVダウンロード</a></p>
        <p><a href=" order_download.php" class="sub__btn sub2__btn">日付選択へ</a></p>
      </div>
      <p><a href="../staff_login/staff_top.php" class="link-back link-back2">トップメニューへ</a></p>
    </div>
  </main>

</body>

</html>