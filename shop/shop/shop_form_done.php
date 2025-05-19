<?php
// PHPMailer メール送信ライブラリを使う
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require('../PHPMailer/src/PHPMailer.php');
require('../PHPMailer/src/Exception.php');
require('../PHPMailer/src/SMTP.php');
// PHPMailer メール送信ライブラリを使う


session_start();
session_regenerate_id(true);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>


  <?php
  try {

    // filter_input を使うと、$_POST データ取得時に無害化処理をオプションで実行できる
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
    $honbun .= "\n";
    $honbun .= "ご注文商品\n";
    $honbun .= "--------------------\n";

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

    // データベースをロックする
    $sql = 'LOCK TABLES dat_sales WRITE,dat_sales_product WRITE, dat_member WRITE';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    // 会員登録する
    $lastmembercode = 0;
    if ($chumon == 'chumontouroku') {
      $sql = 'INSERT INTO dat_member(password,name,email,postal1,postal2,address,tel,danjo,born) VALUES(?,?,?,?,?,?,?,?,?)';
      $stmt = $dbh->prepare($sql);

      // 男性:1 女性:2
      if ($danjo == 'dan') {
        $danjo_val = 1;
      } else {
        $danjo_val = 2;
      }

      $data = [];
      $data = [md5($pass), $onamae, $email, $postal1, $postal2, $address, $tel, $danjo_val, $birth];
      $stmt->execute($data);

      $sql = 'SELECT LAST_INSERT_ID()';
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      $lastmembercode = $rec['LAST_INSERT_ID()'];
    }

    // 購入データを登録する
    $sql = 'INSERT INTO dat_sales(code_member,name,email,postal1,postal2,address,tel) VALUES(?,?,?,?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    $data = [];
    $data = [$lastmembercode, $onamae, $email, $postal1, $postal2, $address, $tel];
    $stmt->execute($data);

    $sql = 'SELECT LAST_INSERT_ID()';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    $lastcode = $rec['LAST_INSERT_ID()'];

    for ($i = 0; $i < $max; $i++) {
      $sql = 'INSERT INTO dat_sales_product(code_sales,code_product,price,quantity) VALUES(?,?,?,?)';
      $stmt = $dbh->prepare($sql);
      $data = [];
      $data = [$lastcode, $cart[$i], $kakaku[$i], $kazu[$i]];
      $stmt->execute($data);
    }


    // アンロックする
    $sql = 'UNLOCK TABLES';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $dbh = null;

    if ($chumon == 'chumontouroku') {
      print '会員登録しました。';
      print '<br>';
      print '次回からはメールアドレスとパスワードでログインしてください。';
      print '<br>';
      print 'ご注文が簡単にできるようになります。';
      print '<br>';
    }

    $honbun .= "送料は無料です。\n";
    $honbun .= "--------------------\n";
    $honbun .= "\n";
    $honbun .= "代金は以下の口座にお振込ください。\n";
    $honbun .= "ろくまる銀行 やさい支店 普通口座 １２３４５６７\n";
    $honbun .= "入金確認が取れ次第、梱包、発送させていただきます。\n";
    $honbun .= "\n";
    if ($chumon == 'chumontouroku') {
      $honbun .= '会員登録しました。';
      $honbun .= "\n";
      $honbun .= '次回からはメールアドレスとパスワードでログインしてください。';
      $honbun .= "\n";
      $honbun .= 'ご注文が簡単にできるようになります。';
      $honbun .= "\n";
    }
    $honbun .= "□□□□□□□□□□□□□□\n";
    $honbun .= "　～安心野菜のろくまる農園～\n";
    $honbun .= "\n";
    $honbun .= "○○県六丸郡六丸村123-4\n";
    $honbun .= "電話 090-6060-xxxx\n";
    $honbun .= "メール info@rokumarunouen.co.jp\n";
    $honbun .= "□□□□□□□□□□□□□□\n";

    // メール本文を画面に出して確認する
    // print nl2br($honbun);

    /*
    $title = 'ご注文ありがとうございます。';
    $header = 'From:info@rokumarunouen.co.jp';
    $honbun = html_entity_decode($honbun, ENT_QUOTES, 'UTF-8');
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");
    mb_send_mail($email, $title, $honbun, $header);

    $title = 'お客様からご注文がありました。';
    $header = 'From:info@rokumarunouen.co.jp';
    $honbun = html_entity_decode($honbun, ENT_QUOTES, 'UTF-8');
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");
    mb_send_mail("info@rokumarunouen.co.jp", $title, $honbun, $header);
*/

    // ロリポップなどの外部サーバでメール送信する
    $phpmailer = new PHPMailer();
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.lolipop.jp';
    $phpmailer->SMTPAuth = true;
    $phpmailer->SMTPSecure = 'ssl';
    $phpmailer->Port = 465;
    $phpmailer->Username = 'info@mizuno.floppy.jp'; // 自分のメールアドレス
    $phpmailer->Password = '5N-p3_-7s-L5nB-4'; // 自分のパスワード
    $phpmailer->CharSet = 'utf-8';


    $title = 'ご注文ありがとうございます。';

    $phpmailer->setFrom('info@mizuno.floppy.jp', 'ショップ');
    $phpmailer->addAddress($email, $onamae); // 送信したい相手
    // 送信内容設定
    $phpmailer->Subject = $title;
    $phpmailer->Body    = $honbun;
    $phpmailer->send();


    $phpmailer->setFrom('info@mizuno.floppy.jp', 'ショップ'); // 送信者
    $phpmailer->addAddress('info@mizuno.floppy.jp', 'ショップ');   // 宛先
    $title = 'お客様からご注文がありました。';
    $phpmailer->Subject = $title;
    $phpmailer->Body    = $honbun;
    $phpmailer->send();
  } catch (Exception $e) {
    print 'ただいま障害により大変ご迷惑をお掛けしております。';
    print $e->getMessage(); // エラーメッセージを出す
    exit();
  }

  ?>

  <br>
  <a href="shop_list.php">商品画面へ</a>

</body>

</html>