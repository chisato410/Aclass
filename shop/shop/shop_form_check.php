<!DOCTYPE html>
<html lang="ja">

<head>
  <?php include '../common/head.php'; ?>
  <title>注文確認 | ろくまる農園</title>
</head>

<body>
  <?php include '../common/header.php'; ?>

  <main class="main">
    <div class="main__inner">
      <?php
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
        print 'お名前が入力されていません。<br><br>';
      } else {
        print '<dl>';
        print '<dt>お名前</dt>';
        print '<dd class="member-info">' . htmlspecialchars($onamae, ENT_QUOTES, 'UTF-8') . '</dd>';
        print '</dl>';
      }

      if (!preg_match('/\A[\w\-\.]+\@[\w\-\.]+\.([a-z]+)\z/', $email)) {
        $okflg = false;
        print 'メールアドレスを正確に入力してください。<br><br>';
      } else {
        print '<dt>メールアドレス</dt>';
        print '<dd class="member-info">' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '</dd>';
        print '</dl>';
      }

      if (!preg_match('/\A[0-9]+\z/', $postal1)) {
        $okflg = false;
        print '郵便番号は半角数字で入力してください。<br><br>';
      } else {
        print '<dt>郵便番号</dt>';
        print '<dd class="member-info">' . htmlspecialchars($postal1, ENT_QUOTES, 'UTF-8') . '-' . htmlspecialchars($postal2, ENT_QUOTES, 'UTF-8') . '</dd>';
        print '</dl>';
      }

      if (!preg_match('/\A[0-9]+\z/', $postal2)) {
        $okflg = false;
        print '郵便番号は半角数字で入力してください。<br><br>';
      }

      if ($address == '') {
        $okflg = false;
        print '住所が入力されていません。<br><br>';
      } else {
        print '<dt>住所</dt>';
        print '<dd class="member-info">' . htmlspecialchars($address, ENT_QUOTES, 'UTF-8') . '</dd>';
        print '</dl>';
      }

      if (!preg_match('/\A\d{2,5}-?\d{2,5}-?\d{4,5}\z/', $tel)) {
        $okflg = false;
        print '電話番号を正確に入力してください。<br><br>';
      } else {
        print '<dt>電話番号</dt>';
        print '<dd class="member-info">' . htmlspecialchars($tel, ENT_QUOTES, 'UTF-8') . '</dd>';
        print '</dl>';
      }

      if ($chumon == 'chumontouroku') {
        if ($pass == '') {
          $okflg = false;
          print 'パスワードが入力されていません<br><br>';
        }
        if ($pass != $pass2) {
          $okflg = false;
          print 'パスワードが一致しません<br><br>';
        }
        print '<dl>';
        print '<dt>性別</dt>';
        print '<dd class="member-info">' . (($danjo == 'dan') ? '男性' : '女性') . '</dd>';
        print '</dl>';

        print '<dl>';
        print '<dt>生まれた年</dt>';
        print '<dd class="member-info">' . htmlspecialchars($birth, ENT_QUOTES, 'UTF-8') . '年代</dd>';
        print '</dl>';
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
        print '<div class="form-actions">';
        print '<input type="button" onclick="history.back()" value="戻る" class="link-back">';
        print '<input type="submit" value="OK" class="main__submit">';
        print '</div>';
        print '</form>';
      } else {
        print '<form>';
        print '<input type="button" onclick="history.back()" value="戻る" class="link-back">';
        print '</form>';
      }
      ?>
    </div>
  </main>

</body>

</html>