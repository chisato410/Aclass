<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <?php
  $gakunen = filter_input(INPUT_POST, 'gakunen', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  switch ($gakunen) {
    case '1':
      $kousha = 'あなたの校舎は南校舎です。';
      $bukatsu = '部活動にはスポーツ系と文科系があります。';
      $mokuhyou = 'まずは学校に慣れましょう。';
      break;

    case '2':
      $kousha = 'あなたの校舎は西校舎です。';
      $bukatsu = '学園祭目指して全力で取り組みましょう。';
      $mokuhyou = '今しかできないことを見つけよう。';
      break;

    case '3':
      $kousha = 'あなたの校舎は東校舎です。';
      $bukatsu = '受験に就職に忙しくなります。後輩へ譲っていきましょう。';
      $mokuhyou = '将来への道を作ろう。';
      break;

    default:
      $kousha = 'あなたの校舎は３年生と同じです。';
      $bukatsu = '部活動はありません。';
      $mokuhyou = '早く卒業しましょう。';
      break;
  }

  print '校舎　' . $kousha . '<br>';
  print '部活　' . $bukatsu . '<br>';
  print '目標　' . $mokuhyou . '<br>';
  ?>

</body>

</html>