<?php
// PHPMailer メール送信ライブラリを使う
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// 設置した場所のパスを指定する
require('../PHPMailer/src/PHPMailer.php');
require('../PHPMailer/src/Exception.php');
require('../PHPMailer/src/SMTP.php');

session_start();
session_regenerate_id(true);
?>

<!DOCTYPE html>
<html lang="ja"> <!-- lang属性をjaに変更 -->

<head>
  <meta charset="UTF-8">
  <title>注文完了 | ろくまる農園</title>
  <?php include '../common/head.php'; ?>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php
      try {
        $onamae = filter_input(INPUT_POST, 'onamae', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $postal1 = filter_input(INPUT_POST, 'postal1', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $postal2 = filter_input(INPUT_POST, 'postal2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $tel = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $chumon = filter_input(INPUT_POST, 'chumon', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $danjo = filter_input(INPUT_POST, 'danjo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $birth = filter_input(INPUT_POST, 'birth', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $honbun = '';
        $honbun .= $onamae . "様\n\nこのたびはご注文ありがとうございました。\n";
        $honbun .= "\nご注文商品\n--------------------\n";
        $cart = $_SESSION['cart'];
        $kazu = $_SESSION['kazu'];
        $max = count($cart);
        $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
        $user = 'root';
        $password = 'root';
        $dbh = new PDO($dsn, $user, $password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        for ($i = 0; $i < $max; $i++) {
          $sql = 'SELECT name,price FROM mst_product WHERE code=?';
          $stmt = $dbh->prepare($sql);
          $data[0] = $cart[$i];
          $stmt->execute($data);
          $rec = $stmt->fetch(PDO::FETCH_ASSOC);
          $name = $rec['name'];
          $price = $rec['price'];
          $kakaku[] = $price;
          $suryo = $kazu[$i];
          $shokei = $price * $suryo;
          $honbun .= $name . ' ';
          $honbun .= $price . '円 x ';
          $honbun .= $suryo . '個 = ';
          $honbun .= $shokei . "円\n";
        }
        // データベースロック
        $sql = 'LOCK TABLES dat_sales WRITE, dat_sales_product WRITE, dat_member WRITE';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        // 会員登録
        $lastmembercode = 0;
        if ($chumon == 'chumontouroku') {
          $sql = 'INSERT INTO dat_member(password,name,email,postal1,postal2,address,tel,danjo,born) VALUES(?,?,?,?,?,?,?,?,?)';
          $stmt = $dbh->prepare($sql);
          $danjo_val = ($danjo == 'dan') ? 1 : 2;
          $data = [md5($pass), $onamae, $email, $postal1, $postal2, $address, $tel, $danjo_val, $birth];
          $stmt->execute($data);
          $sql = 'SELECT LAST_INSERT_ID()';
          $stmt = $dbh->prepare($sql);
          $stmt->execute();
          $rec = $stmt->fetch(PDO::FETCH_ASSOC);
          $lastmembercode = $rec['LAST_INSERT_ID()'];
        }
        // 購入データ登録（★★★ここを修正★★★）
        $sql = 'INSERT INTO dat_sales(code_member, name, email, postal1, postal2, address, tel, date) VALUES(?,?,?,?,?,?,?,NOW())';
        $stmt = $dbh->prepare($sql);
        $data = [$lastmembercode, $onamae, $email, $postal1, $postal2, $address, $tel];
        $stmt->execute($data);
        $sql = 'SELECT LAST_INSERT_ID()';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastcode = $rec['LAST_INSERT_ID()'];
        // 購入商品の登録
        for ($i = 0; $i < $max; $i++) {
          $sql = 'INSERT INTO dat_sales_product(code_sales,code_product,price,quantity) VALUES(?,?,?,?)';
          $stmt = $dbh->prepare($sql);
          $data = [$lastcode, $cart[$i], $kakaku[$i], $kazu[$i]];
          $stmt->execute($data);
        }
        // アンロック
        $sql = 'UNLOCK TABLES';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $dbh = null;
        if ($chumon == 'chumontouroku') {
          print '会員登録しました。<br>';
          print '次回からはメールアドレスとパスワードでログインしてください。<br>';
          print 'ご注文が簡単にできるようになります。<br>';
        }
        $honbun .= "送料は無料です。\n--------------------\n\n";
        $honbun .= "代金は以下の口座にお振込ください。\n";
        $honbun .= "ろくまる銀行 やさい支店 普通口座 １２３４５６７\n";
        $honbun .= "入金確認が取れ次第、梱包、発送させていただきます。\n\n";
        if ($chumon == 'chumontouroku') {
          $honbun .= "会員登録しました。\n次回からはメールアドレスとパスワードでログインしてください。\nご注文が簡単にできるようになります。\n";
        }
        $honbun .= "□□□□□□□□□□□□□□\n";
        $honbun .= "　～安心野菜のろくまる農園～\n";
        $honbun .= "○○県六丸郡六丸村123-4\n";
        $honbun .= "電話 090-6060-xxxx\n";
        $honbun .= "メール info@rokumarunouen.co.jp\n";
        $honbun .= "□□□□□□□□□□□□□□\n";
        // PHPMailer設定
        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.lolipop.jp';
        $phpmailer->SMTPAuth = true;
        $phpmailer->SMTPSecure = 'ssl';
        $phpmailer->Port = 465;
        $phpmailer->Username = 'info@mizuno.floppy.jp';
        $phpmailer->Password = '5N-p3_-7s-L5nB-4';
        $phpmailer->CharSet = 'utf-8';
        // お客様へメール
        $phpmailer->setFrom('info@mizuno.floppy.jp', 'ショップ');
        $phpmailer->addAddress($email, $onamae);
        $phpmailer->Subject = 'ご注文ありがとうございます。';
        $phpmailer->Body = $honbun;
        $phpmailer->send();
        // 自分へメール
        $phpmailer->clearAddresses();
        $phpmailer->addAddress('info@mizuno.floppy.jp', 'ショップ');
        $phpmailer->Subject = 'お客様からご注文がありました。';
        $phpmailer->Body = $honbun;
        $phpmailer->send();
      } catch (Exception $e) {
        print 'ただいま障害により大変ご迷惑をお掛けしております。<br>';
        print $e->getMessage();
        exit();
      }
      ?>
      <br>
      <a href="shop_list.php">商品画面へ</a>
    </div>
  </main>

</body>

</html>