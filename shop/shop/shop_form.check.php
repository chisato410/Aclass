<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  // filter_input を使うと、$_POST データ取得時に無害化処理をオプションで実行できる
  $onamae = filter_input(INPUT_POST, 'onamae', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $postal1 = filter_input(INPUT_POST, 'postal1', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $postal2 = filter_input(INPUT_POST, 'postal2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $tel = filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $chumon = filter_input(INPUT_POST, 'chumon', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $pass2 = filter_input(INPUT_POST, 'pass2', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $danjo = filter_input(INPUT_POST, 'danjo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $birth = filter_input(INPUT_POST, 'birth', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  $okflg = true;

  if ($onamae == '') {
    $okflg = false;
    print 'お名前が入力されていません。';
    print '<br>';
    print '<br>';
  } else {
    print 'お名前: ';
    print '<br>';
    print $onamae;
    print '<br>';
    print '<br>';
  }

  if (preg_match('/\A[\w\-\.]+\@[\w\-\.]+\.([a-z]+)\z/', $email) == 0) {
    $okflg = false;
    print 'メールアドレスを正確に入力してください。';
    print '<br>';
    print '<br>';
  } else {
    print 'メールアドレス';
    print '<br>';
    print $email;
    print '<br>';
    print '<br>';
  }

  if (preg_match('/\A[0-9]+\z/', $postal1) == 0) {
    $okflg = false;
    print '郵便番号は半角数字で入力してください。';
    print '<br>';
    print '<br>';
  } else {
    print '郵便番号';
    print '<br>';
    print $postal1;
    print '-';
    print $postal2;
    print '<br>';
    print '<br>';
  }

  if (preg_match('/\A[0-9]+\z/', $postal2) == 0) {
    $okflg = false;
    print '郵便番号は半角数字で入力してください。';
    print '<br>';
    print '<br>';
  }

  if ($address == '') {
    $okflg = false;
    print '住所が入力されていません。';
    print '<br>';
    print '<br>';
  } else {
    print '住所';
    print '<br>';
    print $address;
    print '<br>';
    print '<br>';
  }

  if (preg_match('/\A\d{2,5}-?\d{2,5}-?\d{4,5}\z/', $tel) == 0) {
    $okflg = false;
    print '電話番号を正確に入力してください。';
    print '<br>';
    print '<br>';
  } else {
    print '電話番号';
    print '<br>';
    print $tel;
    print '<br>';
    print '<br>';
  }

  if ($chumon == 'chumontouroku') {
    if ($pass == '') {
      $okflg = false;
      print 'パスワードが入力されていません';
      print '<br>';
      print '<br>';
    }
    if ($pass != $pass2) {
      $okflg = false;
      print 'パスワードが一致しません';
      print '<br>';
      print '<br>';
    }
    print '性別';
    print '<br>';
    if ($danjo = 'dan') {
      print '男性';
    } else {
      print '女性';
    }
    print '<br>';
    print '<br>';

    print '生まれた年';
    print '<br>';
    print $birth;
    print '年代';
    print '<br>';
    print '<br>';
  }
  if ($okflg) {
    print '<form method="post" action="shop_form_done.php">';
    print '<input type="hidden" name="onamae" value="' . $onamae . '">';
    print '<input type="hidden" name="email" value="' . $email . '">';
    print '<input type="hidden" name="postal1" value="' . $postal1 . '">';
    print '<input type="hidden" name="postal2" value="' . $postal2 . '">';
    print '<input type="hidden" name="address" value="' . $address . '">';
    print '<input type="hidden" name="tel" value="' . $tel . '">';
    print '<input type="hidden" name="chumon" value="' . $chumon . '">';
    print '<input type="hidden" name="pass" value="' . $pass . '">';
    print '<input type="hidden" name="danjo" value="' . $danjo . '">';
    print '<input type="hidden" name="birth" value="' . $birth . '">';
    print '<input type="button" onclick="history.back()" value="戻る">';
    print '<input type="submit" value="ＯＫ"><br />';
    print '</form>';
  } else {

    print '<form>';
    print '<input type="button" onclick="history.back()" value="戻る">';
    print '</form>';
  }

  ?>

</body>

</html>