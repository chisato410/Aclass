<?php
session_start();
session_regenerate_id(true);
if (isset($_SESSION['login']) == false) {
  print 'ログインしていません<br>';
  print '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
  exit();
}
?>
<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <title> ろくまる農園 注文ダウンロード完了</title>
</head>

<body>

  <?php
  $year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $month = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $day = filter_input(INPUT_POST, 'day', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $y_m_d = $year . '-' . $month . '-' . $day;
  try {

    // データベースへ接続する
    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8mb4';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // データを表示する
    $sql = 'SELECT
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
dat_sales, dat_sales_product, mst_product
WHERE
dat_sales.code=dat_sales_product.code_sales
AND dat_sales_product.code_product=mst_product.code
AND substr(dat_sales.date,1,10)=?';
    $stmt = $dbh->prepare($sql);
    $data = [$y_m_d];
    $stmt->execute($data);
    $dbh = null;

    $csv = '注文コード,注文日時,会員番号,お名前,メール,郵便番号,住所,TEL,商品コード,商品名,価格,数量';
    $csv .= "\n";

    while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $csv .= $rec['code'];
      $csv .= ",";
      $csv .= $rec['date'];
      $csv .= ",";
      $csv .= $rec['code_member'];
      $csv .= ",";
      $csv .= $rec['dat_sales_name'];
      $csv .= ",";
      $csv .= $rec['email'];
      $csv .= ",";
      $csv .= $rec['postal1'] . '-' . $rec['postal2'];
      $csv .= ",";
      $csv .= $rec['address'];
      $csv .= ",";
      $csv .= $rec['tel'];
      $csv .= ",";
      $csv .= $rec['code_product'];
      $csv .= ",";
      $csv .= $rec['price'];
      $csv .= ",";
      $csv .= $rec['quantity'];
      $csv .= "\n";
    }
    $file = fopen('./chumon.csv', 'w');
    $csv = mb_convert_encoding($csv, 'SJIS', 'UTF-8');
    fputs($file, $csv);
    fclose($file);
  } catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をおかけしております。';
    print $e->getMessage(); // エラーメッセージを出す
    exit();
  }
  ?>
  <a href="chumon.csv">注文データのCSVダウンロード</a>
  <br>
  <a href="order_download.php">日付選択へ</a>
  <br>
  <a href="../staff_login/staff_top.php">トップメニューへ</a>
  <br>
</body>

</html>