<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php

  require_once('../common/common.php');

  $seireki = filter_input(INPUT_POST, 'seireki', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  $wareki = gengo($seireki);
  print $wareki;

  ?>

</body>

</html>